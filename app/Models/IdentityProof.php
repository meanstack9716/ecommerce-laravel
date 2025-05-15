<?php

namespace App\Models;

use App\Enums\AddressType;
use MongoDB\Laravel\Eloquent\Model;
use App\Constants\Constants;

class IdentityProof extends Model
{
    //
    protected $fillable = [
        'user_id',
        'seller_id',
        'pan_number',
        'pan_front_path',
        'pan_back_path',
        'pan_verify_status',
        'id_type',
        'id_number',
        'id_front_path',
        'id_back_path',
        'id_verify_status',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proof) {     
            $proof->pan_verify_status = Constants::STATUS_PENDING;
            $proof->id_verify_status = Constants::STATUS_PENDING;
        });
    }

    public $timestamps = true;

}
