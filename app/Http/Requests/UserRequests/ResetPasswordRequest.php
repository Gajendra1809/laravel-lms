<?php

namespace App\Http\Requests\UserRequests;

use App\Http\Requests\BaseRequest;

class ResetPasswordRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:150', 'exists:users,email'],
            'token' => ['required'],
            'password' => ['required', 'string', 'min:8', 'max:16', 'confirmed'],
        ];
    }
}
