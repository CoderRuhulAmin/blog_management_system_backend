<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
       //
    }

    public function register(RegistrationRequest $request){

        return $this->authService->userRegister($request);

    }
    public function login(LoginRequest $request){
        
        return $this->authService->userLogin($request);
        
    }
}
