<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductType extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'img_path',
        'category_id',
        'sub_category_id'
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
    

    public $timestamps = true;

}
