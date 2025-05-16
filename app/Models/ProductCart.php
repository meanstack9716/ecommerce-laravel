<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;

use MongoDB\Laravel\Eloquent\Model;

class ProductCart extends Model
{
    //
    protected $fillable = [
        'product_id',
        'user_id',
        'selected_size',
        'selected_color',
        'selected_color_name',
        'quantity',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'product_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }

    protected $with = ['product'];
    public $timestamps = true;

}
