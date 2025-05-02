<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;

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
        'img_path'
    ];

    protected $appends = [
        'img_url'
    ];

    public function getImgUrlAttribute()
    {
        if ($this->img_path) {
            return Storage::url($this->img_path);
        }
        return null;
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id', '_id');
    }

    public function subSubCategories()
    {
        return $this->hasMany(SubSubCategory::class, 'category_id', '_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', '_id');
    }

    public $timestamps = true;

}
