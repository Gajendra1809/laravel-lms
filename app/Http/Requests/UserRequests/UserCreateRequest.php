<?php

namespace App\Http\Requests\UserRequests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:16', 'confirmed'],
            'role' => [Rule::in(['1', '2', '3'])]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.max' => 'Name must be less than 100 characters',
            'email.required' => 'Email is required',
            'email.max' => 'Email must be less than 150 characters',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password must be less than 16 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ];
    }
}
