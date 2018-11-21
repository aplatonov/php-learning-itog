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

    /**
     * list users api
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select(['id', 'login', 'name', 'phone', 'valid', 'confirmed', 'last_login'])->get();

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully.');
    }

    /**
     * confirm user api
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        $user = User::find($id);
        if (Auth::user()->isAdmin() && $user) {
            $user->confirmed = 1;
            $user->save();
            return $this->sendResponse(
                array_only($user->toArray(), ['id', 'name']),
                'User confirmed successfully.'
            );
        }

        return $this->sendError('Operation not permit.', [],401);
    }
}
