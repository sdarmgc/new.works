<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Country extends Model
{
    // Relationships -----------------------------------------------------------

    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'user_country');
    }
}
