<?php

namespace App\Policies;

use App\Models\Metadata;
use App\Models\User;

class MetaDataPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Metadata $metadata): bool
    {
        if ($user->hasRole('author')) {
            return $user->author->id == $metadata->author_id;
        }
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Metadata $metadata): bool
    {
        if ($user->hasRole('author')) {
            return $user->author->id == $metadata->author_id && $metadata->status != 'approve';
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Metadata $metadata): bool
    {
        if ($user->hasRole('author')) {
            return $user->author->id == $metadata->author_id && $metadata->status != 'approve';
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Metadata $metadata): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Metadata $metadata): bool
    {
        return false;
    }
}
