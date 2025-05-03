<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'details',
        'price',
        'discount',
        'sku',
        'stock_quantity',
        'brand',
        'thumbnail_path',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'category_id',
        'thumbnail_path',
        'sub_category_id',
        'sub_sub_category_id'
    ];

    protected $appends = [
        'thumbnail_url',
        'sale_price'
    ];

    public function getSalePriceAttribute()
    {
        if ($this->discount) {
            return $this->price - min($this->discount, 100) * $this->price / 100;
        }
        return $this->price;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::url($this->thumbnail_path);
        }
        return null;
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', '_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', '_id');
    }

    public function subSubCategory()
    {
        return $this->belongsTo(SubSubCategory::class, 'sub_sub_category_id', '_id');
    }

    public function gallery()
    {
        return $this->hasMany(ProductGallery::class, 'product_id', '_id');
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id', '_id'); // One product can have many sizes
    }
    
    protected $with = ['category', 'subCategory', 'subSubCategory'];

    public $timestamps = true;

}
