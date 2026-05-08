<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use App\Models\UserProfile;
// use App\Models\Language;
// use App\Models\Country;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->BelongsToMany(Language::class, 'user_language');
    }

    public function countries(): BelongsToMany
    {
        return $this->BelongsToMany(Country::class, 'user_country');
    }

    /*
    |--------------------------------------------------------------------------
    | Convenience Accessors (optional but VERY useful)
    |--------------------------------------------------------------------------
    */

    public function getFirstNameAttribute()
    {
        return $this->profile?->first_name;
    }

    public function getLastNameAttribute()
    {
        return $this->profile?->last_name;
    }

    public function getFullNameAttribute()
    {
        if ($this->profile) {
            return trim($this->profile->first_name . ' ' . $this->profile->last_name);
        }

        return $this->name;
    }

    // Filament
    public function canAccessPanel(Panel $panel): bool
    {
        // Only users with the 'admin' role or specific permission can enter the panel
        if ($panel->getId() === 'admin') {
            return $this->hasRole('administrator') || $this->can('access_admin');
        }
        else
            return true;
    }
}
