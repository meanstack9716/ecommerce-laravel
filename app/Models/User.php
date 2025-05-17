<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use App\Constants\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'gender',
        'profile_path',
        'is_admin',
        'role_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'created_at',
        'role_id',
        'profile_path'
    ];

    protected $appends = [
        'profile_url'
    ];

    public function getProfileUrlAttribute()
    {
        if ($this->profile_path) {
            return Storage::url($this->profile_path);
        }
        return null;
    }
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function sellerDetails()
    {
        return $this->hasOne(Seller::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function identityProof()
    {
        return $this->hasOne(IdentityProof::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', '_id');
    }

    public function cartItems()
    {
        return $this->hasMany(ProductCart::class, 'user_id', '_id');
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'user_id', '_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Get the default 'user' role from the roles collection
            $defaultRole = Role::where('name', Constants::USER_ROLE)->first();
            
            if ($defaultRole) {
                $user->role_id = $defaultRole->_id;
            }
        });
    }

    /**
     * Always append the role relationship when serializing
     */
    protected $with = ['role'];

    public $timestamps = true;
}