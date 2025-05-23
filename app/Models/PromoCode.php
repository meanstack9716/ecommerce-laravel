<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Carbon;

class PromoCode extends Model
{
    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'start_date',
        'expiry_date',
        'max_uses',
        'uses_per_user',
        'only_first_order',
        'used_count',
        'is_active',
        'applicable_to',
        'applicable_products',
        'created_by',
    ];

    protected $casts = [
        'discount_type' => 'string',
        'start_date' => 'datetime',
        'expiry_date' => 'datetime',
        'applicable_products' => 'array',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'created_by', '_id');
    }

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->expiry_date && $now->gt($this->expiry_date)) {
            return false;
        }

        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function incrementUsedCount()
    {
        $this->increment('used_count');
    }

    public $timestamps = true;

}
