<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;

use MongoDB\Laravel\Eloquent\Model;

class ProductReview extends Model
{
    //
    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'review',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'user_id',
    ];

    public function by()
    {
        return $this->belongsTo(User::class, 'user_id', '_id')
            ->select(['first_name', 'last_name', 'email', 'profile_url', 'id']);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', '_id');
    }

    protected $with = ['by'];
    public $timestamps = true;

}
