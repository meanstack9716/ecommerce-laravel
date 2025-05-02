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
        'price',
        'discount',
        'sku',
        'details',
        'stock_quantity',
        'material',
        'brand',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];


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
    

    public $timestamps = true;

}
