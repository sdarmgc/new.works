<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gender',
        'first_name',
        'last_name',
        'phone',
        'responsibility',
        'extra',
        'notify',
        'active',
        'last_login_at',
        'last_login_ip',
        'deleted_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}