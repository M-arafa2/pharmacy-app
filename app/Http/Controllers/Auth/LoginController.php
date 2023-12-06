<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\doctor;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        //$this->middleware('guest:pharmacy')->except('logout');
    }
    protected function authenticated(Request $request, $user)
    {
        $role = Auth::user()->role;
        if($role == 'doctor') {
            $doc = doctor::where('staff_id', Auth::user()->id)->first();
            if($doc->is_banned == 1) {
                $user->assignRole('banned');
            } else {
                $user->assignRole('doctor');
            }
        } else {
            $user->assignRole($role);
        }


    }

}
