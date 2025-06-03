<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'img_path',
    ];

    protected $appends = [
        'img_url',
        'sub_sub_category_count'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($subCategory) {
            if ($subCategory->img_path && Storage::exists($subCategory->img_path)) {
                Storage::delete($subCategory->img_path);
            }
        });
    }

    public function getImgUrlAttribute()
    {
        if ($this->img_path) {
            return Storage::url($this->img_path);
        }
        return null;
    }

    public function getSubSubCategoryCountAttribute()
    {
        return $this->subSubCategories()->count();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', '_id');
    }

    public function subSubCategories()
    {
        return $this->hasMany(SubSubCategory::class, 'sub_category_id', '_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'sub_category_id', '_id');
    }

    public $timestamps = true;

}
