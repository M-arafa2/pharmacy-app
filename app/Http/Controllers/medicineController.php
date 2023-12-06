<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Models\medicine;
use App\Models\order;
use Illuminate\Http\Request;

class medicineController extends Controller
{
    public function store(Request $request)
    {

        $order = order::find($request->id);
        if($order->status == 'New') {
            $this->validate($request, [
                'medicine' => 'required|string',
              'type' => 'required|string',
            'quantity' => 'required|integer',
          'price' => 'required|integer',
          ]);
            $medicine = medicine::create([
                'order_id' => $request->id,
                'name' => $request->medicine,
                'quantity' => $request->quantity,
                'type' => $request->type,
                'price' => $request->price,

            ]);
            $response['medicine'] = $request->medicine ;
            $response['type'] = $request->type;
            $response['quantity'] = $request->quantity;
            $response['price'] = $request->price;
            $response['success'] = 1;
            return $response;
        } else {
            $response['success'] = 0;
            return $response;
        }


    }

}
