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
                'phone_number' => 'sometimes|string|regex:/^[0-9]{10}$/',
                'isUniqueMobileExceptUsers' => true,
            ],
            'imageSchema' => [
                'image' => 'required|image|max:5120'
            ],
            'addClientPersonalSchema' => [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|string|regex:/^[0-9]{10}$/|unique:users,phone_number',
                'address_type' => ['required', Rule::in(AddressType::values())],
                'address_line1' => 'required|string|min:3',
                'address_city' => 'required|string',
                'address_state' => 'required|string|min:3',
                'address_code' => 'required|string|regex:/^[1-9][0-9]{5}$/',
                'address_country' => 'required|string|min:3',
            ],
            'addClientBusinessSchema' => [
                'business_name' => 'required|string|min:4',
                'business_email' => 'required|email|unique:sellers,email',
                'business_type' => ['required', Rule::in(BusinessType::values())],
                'business_mobile' => 'required|string|regex:/^[0-9]{10}$/|unique:sellers,phone_number',
                'gst_num' => 'required|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                'address_line1' => 'required|string|min:3',
                'address_type' => ['required', Rule::in(AddressType::values())],
                'address_city' => 'required|string',
                'address_state' => 'required|string|min:3',
                'address_code' => 'required|string|regex:/^[1-9][0-9]{5}$/',
                'address_country' => 'required|string|min:3',
            ],
            'addClientIdentitySchema' => [
                'pan_number' => 'required|string|max:255',
                'pan_front' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
                'pan_back' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
                'id_type' => ['required', Rule::in(IdentificationType::values())],
                'id_number' => 'required|string|max:255',
                'id_front' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
                'id_back' => 'sometimes|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
            ],
            'addNewCategorySchema' => [
                'category_name' => 'required|string|min:3|max:255',
                'category_description' => 'required|string|min:3|max:255',
                'category_img' => 'required|image|mimes:jpeg,png,jpg|max:5120'
            ],
            'updateCategorySchema' => [
                'category_name' => 'required|string|min:3|max:255',
                'category_description' => 'required|string|min:3|max:255',
                'category_img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
            ],
            'addSubCategorySchema' => [
                'category_type' => 'required|exists:categories,_id',
                'category_name' => 'required|string|min:3|max:255',
                'category_description' => 'required|string|min:3|max:255',
                'category_img' => 'required|image|mimes:jpeg,png,jpg|max:5120'
            ],
            'updateSubCategorySchema' => [
                'category_type' => 'required|exists:categories,_id',
                'category_name' => 'required|string|min:3|max:255',
                'category_description' => 'required|string|min:3|max:255',
                'category_img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
            ],
            'addSubSubCategorySchema' => [
                'category' => 'required|exists:categories,_id',
                'sub_category' => 'required|exists:sub_categories,_id',
                'name' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:3|max:255',
                'img' => 'required|image|mimes:jpeg,png,jpg|max:5120'
            ],
            'updateSubSubCategorySchema' => [
                'category' => 'required|exists:categories,_id',
                'sub_category' => 'required|exists:sub_categories,_id',
                'name' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:3|max:255',
                'img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
            ],
            'addNewProductCategorySchema' => [
                'category' => 'required|exists:categories,_id',
                'sub_category' => 'required|exists:sub_categories,_id',
                'sub_sub_category' => 'required|exists:sub_sub_categories,_id',
            ],
            'addNewProductDetailsSchema' => [
                'product_title' => 'required|min:3',
                'product_description' => 'required|min:10',
                'product_details' => 'required|min:10',
                'product_price' => 'required|numeric|min:0',
                'discount_per' => 'nullable|numeric|lt:100|min:0',
                'product_brand' => 'required|min:3',
                'new_brand' => 'required_if:product_brand,another|nullable|string',
                'product_sku' => 'required',
                'stock_quantity' => 'required|integer|min:0',
            ],
            'addProductVariantsSchema' => [
                'size_type' => 'required',
            ],
            'addProductGallerySchema' => [
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                // 'images' => 'required|array|min:1',
                // 'images.*' => 'required|array|min:1',
                // 'images.*.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            ],
            'addNewProductBrandSchema' => [
                'name' => 'required|string|min:3|max:255|unique:product_brands,name',
                'description' => 'required|string|min:3|max:255',
                'img' => 'required|image|max:5120'
            ],
            'updateProductBrandSchema' => [
                'name' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:3|max:255',
                'img' => 'nullable|image|max:5120'
            ],
            'addProductCartSchema' => [
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|numeric|min:1',
                'selected_size' => 'required',
                'selected_color' => 'required'
            ],
            'removeProductCartSchema' => [
                'item_ids' => 'array|required|min:1',
                'item_ids.*' => 'exists:product_carts,id'
            ],
            'removeWishlistSchema' => [
                'item_ids' => 'array|required|min:1',
                'item_ids.*' => 'exists:wishlists,id'
            ],
            'updateProductCartSchema' => [
                'id' => 'required|exists:product_carts,id',
                'quantity' => 'nullable|numeric|min:1',
            ],
            'addNewAddressSchema' => [
                'contact_name' => 'required|string|min:3',
                'contact_number' => 'required|string|regex:/^[0-9]{10}$/',
                'line1' => 'required|string|min:3',
                'type' => ['required', Rule::in(AddressType::values())],
                'city' => 'required|string',
                'state' => 'required|string|min:2',
                'postal_code' => 'required|string|regex:/^[1-9][0-9]{5}$/',
                'country' => 'required|string|min:3',
            ],
            'updateAddressSchema' => [
                'address_id' => 'required|exists:addresses,id',
                'line1' => 'nullable|string|min:3',
                'type' => ['nullable', Rule::in(AddressType::values())],
                'city' => 'nullable|string',
                'state' => 'nullable|string|min:2',
                'postal_code' => 'nullable|string|regex:/^[1-9][0-9]{5}$/',
                'country' => 'nullable|string|min:3',
            ],
            'createNewOrderSchema' => [
                'shipping_address_id' => 'required|exists:addresses,id',
                'payment_method' => 'required',
                'cart_items_ids' => 'required|array|min:1',
                'cart_items_ids.*' => 'required|exists:product_carts,id'
            ],
            'postNewReviewSchema' => [
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|numeric|min:1|max:5',
                'review' =>'required|string|min:100',
            ],
            'addPromoCodeSchema' => [
                'code' => 'required|string|min:4|max:50|unique:promo_codes,code',
                'discount_type' => 'required',
                'discount_value' => 'required|numeric|min:0',
                'max_discount_amount' => 'nullable|numeric|min:0',
                'min_order_amount' => 'nullable|numeric|min:0',
                'start_date' => 'required|date',
                'expiry_date' => 'nullable|date|after:start_date',
                'max_uses' => 'nullable|integer|min:1',
                'uses_per_user' => 'required|integer|min:1',
                'description' => 'required|string|max:1000',
                'applicable_to' => 'required|in:all,specific',
                'products' => 'required_if:applicable_to,specific|array',
                'products.*' => 'exists:products,id',
                'is_active' => 'boolean',
            ],
            'updatePromoCodeSchema' => [
                'code' => 'required|string|min:4|max:50',
                'discount_type' => 'required',
                'discount_value' => 'required|numeric|min:0',
                'max_discount_amount' => 'nullable|numeric|min:0',
                'min_order_amount' => 'nullable|numeric|min:0',
                'start_date' => 'required|date',
                'expiry_date' => 'nullable|date|after:start_date',
                'max_uses' => 'nullable|integer|min:1',
                'uses_per_user' => 'required|integer|min:1',
                'description' => 'required|string|max:1000',
                'applicable_to' => 'required|in:all,specific',
                'products' => 'required_if:applicable_to,specific|array',
                'products.*' => 'exists:products,id',
                'is_active' => 'boolean',
            ],
            'validPromocodeSchema' => [
                "promo_code" => "required|exists:promo_codes,code",
                'cart_items_ids' => 'required|array|min:1',
                'cart_items_ids.*' => 'required|exists:product_carts,id'
            ],

            // custom error messages
            'errorMessages' => [
                'email.exists' => 'This email is not registered.',
                'role_id.exists' => 'The selected role is invalid.',
                'phone_number.regex' => 'The phone number format is invalid.',
                'gst_num.regex' => 'GST number format is invalid',
                'gst_num.unique' => 'GST number is not valid.',
                'address_type.required' => 'The Address type is required.',
                'address_line1.required' => 'The Street Line1 is required.',
                'address_city.required' => 'The City is required.',
                'address_state.required' => 'The State is required.',
                'address_code.required' => 'The Postal Code is required.',
                'address_code.regex' => 'The Postal Code format is not valid.',
                'address_country.required' => 'The country Name is required.',
                'sizes.required' => 'At least one size is required.',
                'sizes.*.name.required' => 'Each size must have a name.',
                'sizes.*.custom_size.required_if' => 'Custom size value is required when size is custom.',
                'sizes.*.colors.required' => 'Each size must have at least one set of colors.',
                'sizes.*.colors.custom.*.hex.required' => 'Custom color hex value is required.',
                'images.*.image' => 'Only image type files are valid.'
            ]
        ];
    }
}