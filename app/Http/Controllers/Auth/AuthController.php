<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Services\Auth\AuthServic;

class AuthController extends Controller
{
    public function __construct(private AuthServic $authServic)
    {
       //
    }

    public function register(RegistrationRequest $request){

        return $this->authServic->userRegister($request);

    }
    public function login(LoginRequest $request){
        
        return $this->authServic->userLogin($request);
        
    }
}
