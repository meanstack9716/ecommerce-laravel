<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Carbon;

class RewardPoint extends Model
{
    protected $fillable = [
        'user_id',
        'points',
        'reason',
    ];

    protected $hidden = [
        'updated_at',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public $timestamps = true;

}
