<?php

namespace App\Http\Controllers\Auth;

use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgetPasswordRequest;

class PasswordController extends Controller
{
    public function __construct(private AuthService $authService){}
    public function forgetPassword(ForgetPasswordRequest $request){

        return $this->authService->resetPasswordLink($request);
        
    }

    public function resetPassword(ResetPasswordRequest $request) {
        return $this->authService->updatePassword($request);
    }
}
