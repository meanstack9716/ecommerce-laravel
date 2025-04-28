<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class VerificationCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'is_used'
    ];

    protected function casts(): array
    {
        return [
            'code' => 'integer',
            'is_used' => 'boolean'
        ];
    }


    public $timestamps = true;
}
