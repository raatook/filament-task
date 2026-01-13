<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine if the user can view any projects.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // User can view if they have tasks in this project
        return $project->users()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine if the user can create projects.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can restore the project.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the project.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }
}
