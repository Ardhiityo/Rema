<?php

namespace App\Policies;

use App\Models\MetaData;
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
    public function view(User $user, MetaData $meta_data): bool
    {
        if ($user->hasRole('contributor')) {
            return $user->author->id == $meta_data->author_id;
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
    public function update(User $user, MetaData $meta_data): bool
    {
        if ($user->hasRole('contributor')) {
            return $user->author->id == $meta_data->author_id && $meta_data->status != 'approve';
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MetaData $meta_data): bool
    {
        if ($user->hasRole('contributor')) {
            return $user->author->id == $meta_data->author_id && $meta_data->status != 'approve';
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MetaData $meta_data): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MetaData $meta_data): bool
    {
        return false;
    }
}
