<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Repository;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class RepositoryPolicy
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
    public function view(User $user, Repository $repository): bool
    {
        if ($user->hasRole('contributor')) {
            return $user->author->id == $repository->author_id;
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
    public function update(User $user, Repository $repository): bool
    {
        if ($user->hasRole('contributor')) {
            return $user->author->id == $repository->author_id;
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Repository $repository): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Repository $repository): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Repository $repository): bool
    {
        return false;
    }
}
