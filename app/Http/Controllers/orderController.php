<?php

namespace App\Http\Controllers;

use App\Models\medicine;
use App\Models\order;
use Illuminate\Http\Request;
use http\Env\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class orderController extends Controller
{
    // DataTable data
    public function index(Request $request)
    {
        if($request->ajax()) {
            $query = order::with('prescription')
            ->with('user')
            ->with('address.area')
            ->with('pharmacy.staff');


            if(Auth::user()->role == 'doctor') {
                $pharmacy = DB::table('doctors')->where('staff_id', Auth::user()->id)->first();
                $query-> where('orders.pharmacy_id', $pharmacy->id);
            } elseif(Auth::user()->role == 'pharmacy') {
                $pharmacy = DB::table('pharmacies')->where('staff_id', Auth::user()->id)->first();
                $query-> where('orders.pharmacy_id', $pharmacy->id);


            }
            $orders = $query->get();

            return Datatables::of($orders)
            ->addIndexColumn()
            ->addColumn('is_insured', function ($row) {
                if($row->is_insured == 1) {
                    return "insured";
                } else {
                    return "Not Insured";
                }
                return $row->pharmacy->staff->name;
            })
            ->addColumn('address', function ($row) {

                return $row->address->area->name . '-' . $row->address->street_name;
            })
            ->addColumn('action', function ($row) {

                // Update Button
                $updateButton = "<button class='btn btn-sm btn-info updateUser mx-1' data-id='" . $row->id . "' data-bs-toggle='modal' data-bs-target='#updateModal' ><i class='fa-solid fa-pen-to-square'></i></button>";

                // Delete Button
                $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='" . $row->id . "'><i class='fa-solid fa-trash'></i></button>";

                return "<div style='display:flex;' class='justify-content-center'>" . $updateButton . " " . $deleteButton . "</div>";

            })
            ->make();

        } else {
            return view('orders.index');
        }

    }

    public function show(Request $request)
    {


        ## Read POST data
        if($request->ajax()) {
            $id = $request->id;
            $order = order::where('orders.id', $id)
            ->with('medicine')
            ->with('prescription')
            ->with('user')
            ->with('address.area')
            ->with('pharmacy.staff')->first();
            $response = array();
            //if(!empty($order)) {
            $response['images'] = [];
            foreach($order->prescription as $pres) {
                array_push($response['images'], $pres->image);
            }
            $response['username'] = $order->user->name ;
            if($order->is_insured == 1) {
                $response['is_insured'] = 'True';
            } else {
                $response['is_insured'] = 'false';
            }
            $response['address'] = $order->address->area->name . '/street:' . $order->address->street_name .
                                    '/buildingNum:' . $order->address->building_number .
                                    '/floorNum:' . $order->address->floor_number .
                                    '/flatNum:' . $order->address->flat_number ;
            $response['status'] = $order->status;
            $response['meds'] = [];
            foreach($order->medicine as $med) {
                array_push($response['meds'], $med);

            }
            $response['success'] = 1;
            //} else {
            // $response['success'] = 0;
            // }

            return response()->json($response);
        } else {
            return view('orders.edit');
        }

    }

    // Update Employee record
    public function update(Request $request)
    {
        ## Read POST data
        $id = $request->post('id');
        $order = order::where('id', $id)->with('medicine')->first();
        if($order->status == "New" && !empty($order->medicine)) {
            $lineItems = [];
            $totalPrice = 0;

            foreach ($order->medicine as $med) {
                $totalPrice += $med->price;
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $med->name,
                        ],
                        'unit_amount' => $med->price ,
                    ],
                    'quantity' => 1,
                ];

            }
            $stripe = new \Stripe\StripeClient('sk_test_51ODgdRLDhbHov6ScMkETxHD9oneOxLLVfBbBENnKNn0DFzvUsubDttf9km1qHWMtm5XKBHZhmntzJ3Rf4G1PH4Ob007aUVAhJk');
            try {
                $session = $stripe->checkout->sessions->create([
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'success_url' => 'http://localhost:8000/success',
                    'cancel_url' => 'http://localhost:8000/cancel',
                  ]);
                dump($session);
            } catch (Throwable $e) {
                $response['success'] = 0;
                $respone['msg'] = $e;
                return $response;
            }


            $updatedOrder = $order->update(['status' => 'Waiting For Confirmation',
                            'Total_price' => $totalPrice,
                            'session_id' => $session->id,
                            'payment_url' => $session->url,
                            ]);


            if(!empty($updatedOrder)) {
                $response['success'] = 1;
                return $response;
            }

        } else {
            $response['sucess'] = 0;
            return $response;
        }


    }


    public function webhook()
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.

        $stripe = new \Stripe\StripeClient('sk_test_51ODgdRLDhbHov6ScMkETxHD9oneOxLLVfBbBENnKNn0DFzvUsubDttf9km1qHWMtm5XKBHZhmntzJ3Rf4G1PH4Ob007aUVAhJk');

        $endpoint_secret = 'whsec_72c8fd99404a83ce0b74f79006951d1887dfc4411556b6d114b05a6846602073';
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }

// Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;


                $order = Order::where('session_id', $session->id)->first();
                if ($order && $order->status === 'Waiting For Confirmation') {
                    $order->status = 'Confirmed';
                    $order->save();

                }

                // no break
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }
}
