<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'selected_size',
        'selected_color',
        'selected_color_name',
        'quantity',
        'price',
        'discount_percent',
        'final_price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', '_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }
}