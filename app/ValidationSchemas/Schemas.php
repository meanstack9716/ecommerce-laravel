<?php

namespace App\ValidationSchemas;

class Schemas
{
    public static function get(): array
    {
        return [
            'registerSchema' => [
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',
            ],
            'loginSchema' => [
                'email' => 'required|email',
                'password' => 'required|string',
            ],
            'isRegisteredEmailSchema' => [
                'email' => 'required|email|exists:users,email',
            ],
            'verifyEmailSchema' => [
                'code' => 'required|digits:6',
                'email' => 'required|email|exists:users,email'
            ],
            'resetPasswordSchema' => [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8|confirmed',
                'code' => 'required|digits:6',
                'password_confirmation' => 'required',
            ],
            'updateUserSchema' => [
                'first_name' => 'sometimes|string|max:255|min:3',
                'last_name' => 'sometimes|string|max:255|min:3',
                'email' => 'sometimes|email',
                'phone_number' => 'sometimes|string|regex:/^[+]\d{2}?\d{10}$/',
                'isUniqueMobileExceptUsers' => true,
                'isUniqueEmailExceptUsers' => true
            ],
            'imageSchema' => [
                'image' => 'required|image|max:2048'
            ],
            'addClientPersonalSchema' => [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|string|regex:/^[+]\d{2}?\d{10}$/|unique:users,phone_number',
                'address1_line1' => 'required|string|min:3',
                'address1_city' => 'required|string',
                'address1_state' => 'required|string|min:3',
                'address1_code' => 'required|string|min:3',
                'address1_country' => 'required|string|min:3',
            ],
            'addClientBusinessSchema' => [
                'business_name' => 'required|string|min:4',
                'business_type' => 'required'
            ],

            // custom error messages
            'errorMessages' => [
                'email.exists' => 'This email is not registered.',
                'role_id.exists' => 'The selected role is invalid.',
                'phone_number.regex' => 'The phone number format is invalid.',
                'address1_line1.required' => 'The Street Line1 is required.',
                'address1_city.required' => 'The City is required.',
                'address1_state.required' => 'The State is required.',
                'address1_code.required' => 'The Postal Code is required.',
                'address1_country.required' => 'The country Name is required.',
            ]
        ];
    }
}