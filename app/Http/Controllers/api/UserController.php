<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\GenderValidationRule;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use App\Http\Traits\ApiJsonResponse;

class UserController extends Controller
{
    use ApiJsonResponse;
    public function __construct() {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        $validated = $request->validated();
        $image_path = $request->file('image')->store('/images/resource', ['disk' =>   'my_images']);
        $validated['image'] = $image_path;
        if($user = User::create($validated)) {
            //$user->sendEmailVerificationNotification();
            event(new Registered($user));

            return $this->success('', []);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserEditRequest $request)
    {

        $validated = $request->validated();
        if($request->file('image')) {
            $image_path = $request->file('image')->store('/images/resource', ['disk' =>   'my_images']);
            $validated['image'] = $image_path;
        }

        if(User::find(Auth::user()->id)->update($validated)) {
            return $this->success('', []);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
