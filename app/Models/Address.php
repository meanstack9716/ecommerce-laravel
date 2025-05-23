<?php

namespace App\Models;

use App\Enums\AddressType;
use MongoDB\Laravel\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable = [
        'user_id',
        'type',
        'contact_name',
        'contact_number',
        'line1',
        'line2',
        'city',
        'state',
        'postal_code',
        'country',
        'longitude',
        'latitude'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'type' => AddressType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            if (!isset($address->type)) {
                $address->type = 'Home';
            }
        });
    }

    public $timestamps = true;

}
