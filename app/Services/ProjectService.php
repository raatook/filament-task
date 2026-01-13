<?php

namespace App\Services;

use App\DataTransferObjects\ProjectData;
use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    public function createProject(ProjectData $projectData): Project
    {
        $project = $this->projectRepository->create($projectData->toArray());

        // Assign users if provided
        if ($projectData->userIds) {
            $project->users()->sync($projectData->userIds);
        }

        return $project;
    }

    public function updateProject(int $projectId, ProjectData $projectData): bool
    {
        $updated = $this->projectRepository->update($projectId, $projectData->toArray());

        if ($updated && $projectData->userIds !== null) {
            $project = $this->projectRepository->findById($projectId);
            $project->users()->sync($projectData->userIds);
        }

        return $updated;
    }

    public function deleteProject(int $projectId): bool
    {
        return $this->projectRepository->delete($projectId);
    }

    public function getProjectById(int $projectId)
    {
        return $this->projectRepository->findById($projectId);
    }

    public function getAllProjects()
    {
        return $this->projectRepository->findAll();
    }

    public function getUserProjects(int $userId)
    {
        return $this->projectRepository->findByUserId($userId);
    }

    public function getProjectsWithTaskCounts()
    {
        return $this->projectRepository->getProjectsWithTaskCounts();
    }

    public function getOverdueProjects()
    {
        return $this->projectRepository->findOverdueProjects();
    }

    public function isProjectOverdue(Project $project): bool
    {
        return $project->due_date
            && $project->due_date->isPast()
            && $project->tasks()->where('status', '!=', 'done')->exists();
    }
}
