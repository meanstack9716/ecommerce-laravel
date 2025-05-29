<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;

use MongoDB\Laravel\Eloquent\Model;

class ProductBrand extends Model
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

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', '_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($brand) {
            if ($brand->img_path && Storage::exists($brand->img_path)) {
                Storage::delete($brand->img_path);
            }
        });
    }


    public $timestamps = true;

}
