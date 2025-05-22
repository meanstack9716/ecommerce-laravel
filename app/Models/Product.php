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
        'final_price',
        'seller_id',
        'discount_percent',
        'sku',
        'stock_quantity',
        'delivery_days',
        'brand_id',
        'thumbnail_path',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'category_id',
        'seller_id',
        'brand_id',
        'thumbnail_path',
        'sub_category_id',
        'sub_sub_category_id'
    ];

    protected $appends = [
        'thumbnail_url',
    ];

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::url($this->thumbnail_path);
        }
        return null;
    }

    protected static function boot()
    {
        parent::boot();

        // Calculate and store final_price when creating or updating
        static::saving(function ($product) {
            $product->calculateAndStoreFinalPrice();
        });
    }

    public function calculateAndStoreFinalPrice()
    {
        $price = (float)$this->price;
        $discount = $this->discount_percent ? min((float)$this->discount_percent, 100) : 0;
        
        $this->final_price = $discount > 0 
            ? $price - ($price * $discount / 100)
            : $price;
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', '_id')
            ->select(['business_name', 'business_type', 'business_email', 'business_mobile']);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', '_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', '_id');
    }

    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'brand_id', '_id');
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

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id', '_id');
    }

    public function galleryForColor($color)
    {
        return $this->hasMany(ProductGallery::class, 'product_id', '_id')
            ->where('color', $color);
    }
    
    protected $with = ['category', 'subCategory', 'subSubCategory', 'seller'];

    public $timestamps = true;

}
