<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;

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
    protected $redirectTo = '/users/editprofile';

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
            'login' => 'required|max:30|unique:users',
            'email' => 'required|email|max:120|unique:users',
            'password' => 'required|min:6|confirmed',
            'name' => 'required|min:2|max:80',
            'contact_person' => 'required|min:2|max:190',
            'phone' => 'required|max:60',
            'www' => 'max:150|nullable'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        //dd($data);
        $confirmation_code = str_random(32);
        
        $data['link'] = '/register/confirm/' . $confirmation_code;
        
        Mail::send('layouts.mailconfirm', $data, function ($message) use ($data) {
                $message->to($data['email'])
                    ->subject('Confirm registration for ' . $data['login']);
            });

        $user = User::create([
            'login' => $data['login'],
            'email' => $data['email'],
            'name' => $data['name'],
            'contact_person' => $data['contact_person'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
            'confirmation_code' => $confirmation_code,
            'www' => $data['www'],
        ]);

        return $user;
    }
}
