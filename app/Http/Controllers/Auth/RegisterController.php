<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\doctor;
use App\Providers\RouteServiceProvider;
use App\Models\staff;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\doctor
     */
    protected function create(array $data)
    {
        return staff::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'image' => 'image/doctors/default.jpg',
            'national_id' => '34343445343',
            'role' => 'doctor',

        ]);
    }
    protected function gitRedirect()
    {
        return Socialite::driver('github')->redirect();
    }
    protected function gitCallBack(Request $request)
    {
        dump($request);
        $githubUser = Socialite::driver('github')->user();
        $this->socialLogin($githubUser->id, $githubUser->name, $githubUser->email);
        return redirect('/home');


    }
    protected function twitterRedirect()
    {
        return Socialite::driver('twitter')->redirect();
    }
    protected function twitterCallBack(Request $request)
    {
        $twitterUser = Socialite::driver('twitter')->user();
        $this->socialLogin($twitterUser->id, $twitterUser->name, $twitterUser->email);

        return redirect('/home');
    }

    private function socialLogin(string $id, string $name, string $email)
    {
        if($user = staff::where('email', $email)->first()) {
            $user->update([
                'github_id' => $id]);
        } else {
            $user = staff::Create([
                'github_id' => $id,
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(str::random(8)),
                'image' => 'image/doctors/default.jpg',
                'national_id' => rand(200000, 2000000000000000),
                'role' => 'doctor',
            ]);
        }
        Auth::login($user);
        $user->assignRole('doctor');


    }
}
