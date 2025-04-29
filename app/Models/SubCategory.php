<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class SubCategory extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'img_path',
        'category_id'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', '_id');
    }

    public function productTypes()
    {
        return $this->hasMany(ProductType::class, 'sub_category_id', '_id');
    }

    public $timestamps = true;

}
