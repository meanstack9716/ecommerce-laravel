<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class SearchTermAnalytic extends Model
{
    //
    protected $fillable = [
        'keyword',
        'search_count',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', '_id');
    }

    public $timestamps = true;

}