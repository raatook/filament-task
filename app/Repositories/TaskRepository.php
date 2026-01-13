<?php

namespace App\Repositories;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TaskRepository implements TaskRepositoryInterface
{
    public function findById(int $id)
    {
        return Task::find($id);
    }

    public function findByUserId(int $userId): Collection
    {
        return Task::where('user_id', $userId)->get();
    }

    public function findByProjectId(int $projectId): Collection
    {
        return Task::where('project_id', $projectId)->get();
    }

    public function findByStatus(TaskStatus $status): Collection
    {
        return Task::where('status', $status->value)->get();
    }

    public function findUrgentTasks(int $limit = 10): Collection
    {
        return Task::query()
            ->where(function ($query) {
                $query->whereIn('priority', [4, 5])
                    ->orWhere(function ($q) {
                        $q->whereNotNull('due_date')
                            ->where('due_date', '<=', now()->addDays(3))
                            ->where('due_date', '>=', now());
                    });
            })
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function findOverdueTasks(): Collection
    {
        return Task::where('due_date', '<', now())
            ->where('status', '!=', TaskStatus::DONE->value)
            ->get();
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $task = $this->findById($id);

        if (!$task) {
            return false;
        }

        return $task->update($data);
    }

    public function delete(int $id): bool
    {
        $task = $this->findById($id);

        if (!$task) {
            return false;
        }

        return $task->delete();
    }

    public function countByStatus(TaskStatus $status): int
    {
        return Task::where('status', $status->value)->count();
    }

    public function getCompletionRate(?int $userId = null): float
    {
        $query = Task::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $totalTasks = $query->count();

        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = (clone $query)->where('status', TaskStatus::DONE->value)->count();

        return round(($completedTasks / $totalTasks) * 100, 1);
    }
}
