<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Constants\Constants;

class Seller extends Model
{
    //
    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'business_email',
        'business_logo_cloud_id',
        'business_logo',
        'business_mobile',
        'gst_number',
        'status', 
        'processed_by',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'business_logo_cloud_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($seller) {     
            $seller->status = Constants::STATUS_PENDING;
        });
    }

    public $timestamps = true;

}
