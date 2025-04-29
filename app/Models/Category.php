<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'img_path'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id', '_id');
    }

    public function productTypes()
    {
        return $this->hasMany(ProductType::class, 'category_id', '_id');
    }

    public $timestamps = true;

}
