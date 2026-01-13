<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectUserAssignmentService
{
    public function assignUserToProject(int $userId, int $projectId): void
    {
        $project = Project::withoutGlobalScopes()->find($projectId);

        if (!$project) {
            return;
        }

        // Check if user is already assigned
        $isAssigned = $project->users()
            ->where('users.id', $userId)
            ->exists();

        if (!$isAssigned) {
            $project->users()->attach($userId);
        }
    }

    public function unassignUserFromProject(int $userId, int $projectId): void
    {
        $project = Project::withoutGlobalScopes()->find($projectId);

        if (!$project) {
            return;
        }

        $project->users()->detach($userId);
    }

    public function getUserProjects(int $userId)
    {
        return Project::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->get();
    }

    public function getProjectUsers(int $projectId)
    {
        $project = Project::withoutGlobalScopes()->find($projectId);

        if (!$project) {
            return collect();
        }

        return $project->users;
    }
}
