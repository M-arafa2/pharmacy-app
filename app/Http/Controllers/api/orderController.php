<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\order;
use App\Models\address;
use App\Models\pharmacy;
use App\Models\prescription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ApiJsonResponse;
use App\Http\Traits\AssignPharmacy;

class orderController extends Controller
{
    use ApiJsonResponse;
    use AssignPharmacy;
    /**
     * Display a listing of the resource.
     */
    public function __construct() {}
    public function index()
    {
        $orders = order::where('user_id', Auth::user()->id)->with('medicine')->get();

        return $this->success('', OrderResource::collection($orders));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $address = DB::table('addresses')->where('user_id', Auth::user()->id)
                              ->where('is_main', 1)->first();

        if(empty($address)) {
            return $this->failure('add an address or select one of them to be main address before ordering');
        }

        $this->validate($request, [
            'is_insured' => 'required|boolean',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:4048',
        ]);

        $order = order::create([
            'user_id' => Auth::user()->id,
            'address_id' => $address->id,
            'is_insured' => $request->is_insured,
            'status' => 'New',
            'pharmacy_id' => $this->Assign($address->area_id),
            'creator_type' => 'user',
        ]);

        foreach ($request->file('images') as $imagefile) {
            $path = $imagefile->store('/images/prescription', ['disk' =>   'my_images']);
            prescription::create([
                'user_id' => Auth::user()->id,
                'order_id' => $order->id,
                'image' => $path,
            ]);
        }
        return $this->success('', []);

    }


    public function update(Request $request, order $order)
    {
        if($order->status == 'New') {
            $address = DB::table('addresses')->where('user_id', Auth::user()->id)
                              ->where('is_main', 1)->first();
            $this->validate($request, [
                'is_insured' => 'required|boolean',
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:4048',
            ]);

            $order ->update([
                'user_id' => Auth::user()->id,
                'address_id' => $address->id,
                'is_insured' => $request->is_insured,
                'status' => 'New',
                'pharmacy_id' => $this->Assign($address->area_id),
                'creator_type' => 'user',
            ]);
            $pres = prescription::where('order_id', $order->id)->get();
            foreach ($pres as $pre) {
                $pre->delete();
            }
            foreach ($request->file('images') as $imagefile) {
                $path = $imagefile->store('/images/prescription', ['disk' =>   'my_images']);
                prescription::create([
                    'user_id' => Auth::user()->id,
                    'order_id' => $order->id,
                    'image' => $path,
                ]);
            }
            return $this->success('', []);
        } else {
            return $this->failure('Only New Orders can be updated');
        }
    }
    public function cancel(order $order)
    {
        if (($order && $order->status === 'New' or $order && $order->status === 'Waiting For Confirmation') &&
            $order->user_id == Auth::user()->id) {
            $order->status = 'Canceled';
            $order->save();
            return $this->success('', []);
        } else {
            return $this->failure('invalid order');
        }


    }


}
