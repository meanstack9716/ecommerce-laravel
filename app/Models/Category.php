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
        'img_url',
        'sub_category_count',
        'sub_sub_category_count'
    ];

    public function getImgUrlAttribute()
    {
        if ($this->img_path) {
            return Storage::url($this->img_path);
        }
        return null;
    }

    public function getSubCategoryCountAttribute()
    {
        return $this->subCategories()->count();
    }

    public function getSubSubCategoryCountAttribute()
    {
        return $this->subSubCategories()->count();
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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            if ($category->img_path && Storage::exists($category->img_path)) {
                Storage::delete($category->img_path);
            }
        });
    }

    public $timestamps = true;

}
