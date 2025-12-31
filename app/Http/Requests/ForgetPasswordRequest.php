<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class ForgetPasswordRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email'
        ];
    }
}
