<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class GameUser extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'username',
        'password',
        'email',
        'money',
        'danap',
        'level',
        'verified',
        'time',
        'playerId',
        'isLock',
        'isAdmin',
        'lastIP',
        'isLoad',
        'lastlogout',
        'toalGold',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'session',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'isAdmin' => 'boolean',
        'isLock' => 'boolean',
        'isLoad' => 'boolean',
        'verified' => 'boolean',
        'level' => 'integer',
        'money' => 'integer',
        'danap' => 'integer',
        'toalGold' => 'integer',
        'playerId' => 'integer',
        'lastlogout' => 'integer',
        'time' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Set the password attribute.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the status text attribute.
     */
    public function getStatusTextAttribute()
    {
        if ($this->isLock) {
            return 'Bị khóa';
        }

        if ($this->isLoad) {
            return 'Hoạt động';
        }

        return 'Không hoạt động';
    }

    /**
     * Get the vip level text attribute.
     */
    public function getVipLevelTextAttribute()
    {
        return $this->vip > 0 ? "VIP {$this->vip}" : 'Thường';
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('isLoad', true);
    }

    /**
     * Scope a query to only include locked users.
     */
    public function scopeLocked($query)
    {
        return $query->where('isLock', true);
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmin($query)
    {
        return $query->where('isAdmin', true);
    }

    /**
     * Scope a query to only include verified users.
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }
}
