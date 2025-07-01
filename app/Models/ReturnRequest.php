<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $fillable = [
        'order_id',
        'order_item_id',
        'product_id',
        'user_id',
        'seller_id',
        'quantity',
        'reason',
        'status', // pending, approved, rejected, refunded
        'refund_amount',
        'refund_method',
        'admin_notes',
        'refund_status', // pending, processed, failed
        'additional_notes',
        'requested_at',
        'approved_at',
        'refunded_at',
        'processed_by',
        'images'
    ];


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', '_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', '_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }

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
        return $this->hasMany(ReturnItem::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(Seller::class, 'processed_by', '_id');
    }
}