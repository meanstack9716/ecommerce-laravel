<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductGallery extends Model
{
    //
    protected $fillable = [
        'product_id',
        'img_path',
        'color'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'img_path',
        'product_id'
    ];

    protected $appends = [
        'img_url',
    ];

    public function getImgUrlAttribute()
    {
        if ($this->img_path) {
            return Storage::url($this->img_path);
        }
        return null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }

    public $timestamps = true;

}
