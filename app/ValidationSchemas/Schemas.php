<?php

namespace App\ValidationSchemas;

use Illuminate\Validation\Rule;
use App\Enums\BusinessType;
use App\Enums\AddressType;
use App\Enums\IdentificationType;

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
                'image' => 'required|image|max:5120'
            ],
            'addClientPersonalSchema' => [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|string|regex:/^[+]\d{2}?\d{10}$/|unique:users,phone_number',
                'address_line1' => 'required|string|min:3',
                'address_city' => 'required|string',
                'address_state' => 'required|string|min:3',
                'address_code' => 'required|string|min:3',
                'address_country' => 'required|string|min:3',
            ],
            'addClientBusinessSchema' => [
                'business_name' => 'required|string|min:4',
                'business_email' => 'required|email|unique:sellers,email',
                'business_type' => ['required', Rule::in(BusinessType::values())],
                'business_mobile' => 'required|string|regex:/^[+]\d{2}?\d{10}$/|unique:sellers,phone_number',
                'gst_num' => 'required',
                'address_line1' => 'required|string|min:3',
                'address_type' => ['required', Rule::in(AddressType::values())],
                'address_city' => 'required|string',
                'address_state' => 'required|string|min:3',
                'address_code' => 'required|string|min:3',
                'address_country' => 'required|string|min:3',
            ],
            'addClientIdentitySchema' => [
                'pan_number' => 'required|string|max:255',
                'pan_front' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'pan_back' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'id_type' => ['required', Rule::in(IdentificationType::values())],
                'id_number' => 'required|string|max:255',
                'id_front' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'id_back' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ],
            'addNewCategorySchema' => [
                'category_name' => 'required|string|min:3|max:255',
                'category_description' => 'required|string|min:3|max:255',
                'category_img' => 'required|image|mimes:jpeg,png,jpg|max:5120'
            ],
            'addSubCategorySchema' => [
                'category_type' => 'required|exists:categories,_id',
                'category_name' => 'required|string|min:3|max:255',
                'category_description' => 'required|string|min:3|max:255',
                'category_img' => 'required|image|mimes:jpeg,png,jpg|max:5120'
            ],
            'addProductTypeSchema' => [
                'category' => 'required|exists:categories,_id',
                'sub_category' => 'required|exists:sub_categories,_id',
                'name' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:3|max:255',
                'img' => 'required|image|mimes:jpeg,png,jpg|max:5120'
            ],

            // custom error messages
            'errorMessages' => [
                'email.exists' => 'This email is not registered.',
                'role_id.exists' => 'The selected role is invalid.',
                'phone_number.regex' => 'The phone number format is invalid.',
                'address_type.required' => 'The Address type is required.',
                'address_line1.required' => 'The Street Line1 is required.',
                'address_city.required' => 'The City is required.',
                'address_state.required' => 'The State is required.',
                'address_code.required' => 'The Postal Code is required.',
                'address_country.required' => 'The country Name is required.',
            ]
        ];
    }
}