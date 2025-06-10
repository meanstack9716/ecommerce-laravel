<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Carbon;

class PaymentHistory extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'order_ids',
        'total_amount',
        'reference_id',
        'payment_gateway',
        'gateway_payment_id',
        'paid_at'
    ];

    protected $casts = [
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public $timestamps = true;

}
