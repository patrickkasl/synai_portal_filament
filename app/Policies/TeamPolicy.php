<?php

namespace App\Policies;

use App\Models\User;

class TeamPolicy
{
    /**
     * Determine whether the user can view any teams.
     */
    public function viewAny(User $user): bool
    {
        return $user->role->name === 'admin';
    }

    /**
     * Determine whether the user can view the team.
     */
    public function view(User $user, $team): bool
    {
        return $user->role->name === 'admin' || $user->teams->contains($team);
    }

    /**
     * Determine whether the user can create teams.
     */
    public function create(User $user): bool
    {
        return $user->role->name === 'admin';
    }

    /**
     * Determine whether the user can update the team.
     */
    public function update(User $user, $team): bool
    {
        return $user->role->name === 'admin' || $user->teams->contains($team);
    }

    /**
     * Determine whether the user can delete the team.
     */
    public function delete(User $user, $team): bool
    {
        return $user->role->name === 'admin' || $user->teams->contains($team);
    }
}
