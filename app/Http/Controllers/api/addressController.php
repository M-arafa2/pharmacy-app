<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressCreateRequest;
use App\Http\Requests\AddressEditRequest;
use App\Http\Resources\AddressCollection;
use App\Http\Resources\AddressResource;
use App\Models\address;
use App\Models\area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiJsonResponse;

class addressController extends Controller
{
    use ApiJsonResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success('', AddressResource::collection(address::where('user_id', Auth::user()->id)->with('area')->get()));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(AddressCreateRequest $request)
    {

        $validated = $request->validated();
        if(!empty($request->area_id)) {
            if(!area::find($request->area_id)) {
                return $this->failure('Area doesnt Exist', 404);
            }

        }
        if($request->is_main == 1) {
            $othaddress = address::where('user_id', Auth::user()->id)
                        ->where('is_main', 1)->first();

            if(!empty($othaddress)) {
                $othaddress->update(['is_main' => 0]);
            }
        }
        if(address::create([...$validated,'user_id' => Auth::user()->id])) {


            return $this->success('', [], 201);
        }
    }
    public function show(address $address)
    {
        if($address = address::where([['user_id',Auth::user()->id], ['id' ,$address->id]])->first()) {
            return $this->success('', AddressResource::make($address));
        } else {
            return $this->failure("invalid id");
        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressEditRequest $request, address $address)
    {

        $validated = $request->validated();

        if(!address::where([['user_id',Auth::user()->id], ['id' ,$address->id]])->first()) {

            return $this->failure('There is no address with this id for this user', 404);
        }
        if(!area::find($request->area_id)) {
            return $this->failure('Area doesnt Exist', 404);
        }
        if($address->update($validated)) {
            if($request->is_main == 1) {
                $othaddress = address::where('user_id', Auth::user()->id)
                              ->where('is_main', 1)->first();
                if(!empty($othaddress)) {
                    $othaddress->is_main == 0;
                    $othaddress->save;
                }
            }
            return $this->success('', $address);
        }
    }

    public function destroy(address $address)
    {
        if(address::where([['user_id',Auth::user()->id], ['id' ,$address->id]])->first()) {
            if($address->is_main == 1) {
                $add = address::select('*')->first();
                $add->update(['is_main' => 1]);
            }
            $address->delete();
            return $this->success('', []);
        } else {
            return $this->failure("invalid id");
        }

    }
}
