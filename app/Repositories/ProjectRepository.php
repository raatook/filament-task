<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function findById(int $id)
    {
        return Project::find($id);
    }

    public function findByUserId(int $userId): Collection
    {
        return Project::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->get();
    }

    public function findAll(): Collection
    {
        return Project::all();
    }

    public function create(array $data)
    {
        return Project::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $project = $this->findById($id);

        if (!$project) {
            return false;
        }

        return $project->update($data);
    }

    public function delete(int $id): bool
    {
        $project = $this->findById($id);

        if (!$project) {
            return false;
        }

        return $project->delete();
    }

    public function countAll(): int
    {
        return Project::count();
    }

    public function getProjectsWithTaskCounts(): Collection
    {
        return Project::withCount([
            'tasks',
            'tasks as pending_tasks_count' => fn($query) => $query->where('status', 'pending'),
            'tasks as in_progress_tasks_count' => fn($query) => $query->where('status', 'in_progress'),
            'tasks as done_tasks_count' => fn($query) => $query->where('status', 'done'),
        ])->get();
    }

    public function findOverdueProjects(): Collection
    {
        return Project::where('due_date', '<', now())
            ->whereHas('tasks', function ($query) {
                $query->where('status', '!=', 'done');
            })
            ->get();
    }
}
