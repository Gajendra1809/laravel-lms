<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $uuid = $this->route('uuid');

        return [
            'name' => ['string', 'max:100'],
            'email' => [
                'string',
                'email',
                'max:150',
                Rule::unique('users')->ignore($uuid, 'uuid'),
            ],
            'password' => ['string', 'min:8', 'max:16'],
            'role' => ['string', Rule::in(['1', '2', '3'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Name must be less than 100 characters',
            'email.max' => 'Email must be less than 150 characters',
            'email.unique' => 'Email already exists',
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password must be less than 16 characters',
            'role.in' => 'Role must be "1"=>Super Admin, "2"=>Admin or "3"=>User',
        ];
    }
}
