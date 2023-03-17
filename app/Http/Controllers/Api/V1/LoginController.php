<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ResponseController;
use \App\Models\User;
use Validator;

class LoginController extends Controller
{
    private $response;

    public function __construct(){
        $this->response = new ResponseController;
    }

    public function login(LoginRequest $request){
        if (Auth::attempt($request->only('email', 'password'))){
            return $this->response->sendResponse(true, array("token" => $request->user()->createToken($request->device)->plainTextToken, "minutes_to_expire" => 1440));
        }

        return $this->response->sendError(array("Email or Password incorrect for: tester"), 401);
    }
}