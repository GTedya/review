<?php

namespace App\Policies;

use App\Models\Geo;
use App\Models\User;

class GeoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view geos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Geo $geo): bool
    {
        return $user->can('view geos');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create geos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Geo $geo): bool
    {
        return $user->can('edit geos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Geo $geo): bool
    {
        return $user->can('delete geos');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Geo $geo): bool
    {
        return $user->can('delete geos');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Geo $geo): bool
    {
        return $user->can('force delete geos');
    }
}
