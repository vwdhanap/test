<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        return [
            /**
             * username.
             * 
             * @var string
             * 
             * @example user1
             */
            'username' => 'required|string|max:50|unique:users',
            /**
             * email.
             * 
             * @var string
             * 
             * @example example@gmail.com
             */
            'email' => 'required|string|email|max:255|unique:users',
            /**
             * password.
             * 
             * @var string
             * 
             * @example password
             */
            'password' => 'required|string|min:6|confirmed'
        ];
    }
}
