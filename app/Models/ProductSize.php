<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductSize extends Model
{
    //
    protected $fillable = [
        'product_id',
        'value',
        'size_type'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'size_id', '_id');
    }

    protected $with = ['variants'];

    public $timestamps = true;

}
