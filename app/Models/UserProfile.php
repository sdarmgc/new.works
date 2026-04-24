<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id' => $user->id,
        'gender' => (trim($user->title) == 'Sis.') ? false : true,
        'first_name' => $user->first_name,
        'last_name'  => $user->last_name,
        'phone' => $user->phone,
        'phone_app' => $user->phone_app,
        'extra' => $user->extra,
        'notify' => $user->notify,
        'active' => $user->active,
        'last_login_at' => $user->last_login_at,
        'last_login_ip' => $user->last_login_ip,
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

    public function user()
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