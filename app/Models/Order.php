<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'seller_id',
        'order_number',
        'order_amount',
        'total_amount',
        'contact_name',
        'contact_number',
        'status',
        'shipping_address',
        'shipping_address_type',
        'promo_code_id',
        'promo_code_name',
        'promo_code_applied',
        'promo_code_disount',
        'payment_method',
        'payment_status',
        'order_note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', '_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'order_id', '_id');
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id', '_id');
    }
}