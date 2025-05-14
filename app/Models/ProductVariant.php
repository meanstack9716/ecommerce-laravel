<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductVariant extends Model
{
    //
    protected $fillable = [
        'size_id',
        'value',
        'name',
        'stock_quantity'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'size_id'
    ];

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'size_id', '_id');
    }

    public $timestamps = true;

}
