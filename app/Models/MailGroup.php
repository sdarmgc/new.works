<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\User;

class MailGroup extends Model
{
    protected $fillable = [
        'name',
    ];

    protected static function booted()
    {
        static::deleting(function (Model$model) {
            if ($model->users()->count() > 0) {
                // Prevent deletion and throw error
                throw new \Exception('Cannot delete a MailGroup with active users.');
            }
        });
    }

    // Relationships -----------------------------------------------------------

    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'user_mail_group');
    }
}
