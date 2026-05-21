<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MailGroup;

class MailGroupPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['executive', 'administrator']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['administrator']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MailGroup $model): bool
    {
        return $user->hasRole(['administrator']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MailGroup $model): bool
    {
        return $user->hasRole(['administrator']);
    }

}
