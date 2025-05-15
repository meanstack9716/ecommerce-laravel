<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Constants\Constants;
use App\Enums\BusinessType;

class Seller extends Model
{
    //
    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'business_email',
        'business_mobile',
        'gst_num',
        'status', 
        'processed_by',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'business_type' => BusinessType::class,
    ];

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function identityProof()
    {
        return $this->hasOne(IdentityProof::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id', '_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($seller) {     
            if (empty($seller->status)) {
                $seller->status = Constants::STATUS_PENDING;
            }
        });
    }

    public $timestamps = true;

}
