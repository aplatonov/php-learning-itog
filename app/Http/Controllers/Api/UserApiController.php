<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Users;

class UserApiController extends Controller
{
    public function index()
    {
        return Users::all();
    }
}
