<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|max:30|unique:users',
            'email' => 'required|email|max:120|unique:users',
            'password' => 'required|min:6|confirmed',
            'name' => 'required|min:2|max:80',
            'contact_person' => 'required|min:2|max:190',
            'phone' => 'required|max:60',
            'www' => 'max:150|nullable'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->all();
        $user = User::create([
            'login' => $data['login'],
            'email' => $data['email'],
            'name' => $data['name'],
            'contact_person' => $data['contact_person'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);

        $success['token'] = $user->createToken('PMhelper')->accessToken;
        $success['name'] = $user->login;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if(Auth::attempt(['login' => request('login'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] = $user->createToken('PMhelper')-> accessToken;
            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', [],401);
        }
    }
}
