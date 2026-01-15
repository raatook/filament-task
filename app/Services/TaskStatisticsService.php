<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Collection;

class TaskStatisticsService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function getGlobalStatistics(): array
    {
        return [
            'pending' => $this->taskRepository->countByStatus(TaskStatus::PENDING),
            'in_progress' => $this->taskRepository->countByStatus(TaskStatus::IN_PROGRESS),
            'done' => $this->taskRepository->countByStatus(TaskStatus::DONE),
            'overdue' => $this->taskRepository->findOverdueTasks()->count(),
            'urgent' => Task::whereIn('priority', [4, 5])->count(),
        ];
    }

    public function getUserStatistics(int $userId): array
    {
        $userTasks = $this->taskRepository->findByUserId($userId);

        return [
            'total' => $userTasks->count(),
            'pending' => $userTasks->where('status', TaskStatus::PENDING)->count(),
            'in_progress' => $userTasks->where('status', TaskStatus::IN_PROGRESS)->count(),
            'done' => $userTasks->where('status', TaskStatus::DONE)->count(),
            'completion_rate' => $this->taskRepository->getCompletionRate($userId),
            'overdue' => $userTasks->filter(function ($task) {
                return $task->due_date
                    && $task->due_date->isPast()
                    && $task->status !== TaskStatus::DONE;
            })->count(),
        ];
    }

    public function getAllUsersProductivity(): Collection
    {
        return User::where('role', UserRole::USER)
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn ($query) =>
                    $query->where('status', TaskStatus::DONE),
                'tasks as in_progress_tasks_count' => fn ($query) =>
                    $query->where('status', TaskStatus::IN_PROGRESS),
                'tasks as pending_tasks_count' => fn ($query) =>
                    $query->where('status', TaskStatus::PENDING),
                'tasks as overdue_tasks_count' => fn ($query) =>
                    $query->where('status', '!=', TaskStatus::DONE)
                        ->where('due_date', '<', now()),
            ])
            ->get()
            ->map(function ($user) {
                $completionRate = $user->tasks_count > 0
                    ? round(($user->completed_tasks_count / $user->tasks_count) * 100, 1)
                    : 0;

                return [
                    'user' => $user,
                    'total_tasks' => $user->tasks_count,
                    'completed' => $user->completed_tasks_count,
                    'in_progress' => $user->in_progress_tasks_count,
                    'pending' => $user->pending_tasks_count,
                    'overdue' => $user->overdue_tasks_count,
                    'completion_rate' => $completionRate,
                ];
            });
    }

    public function getMonthlyTaskTrend(): array
    {
        $thisMonth = Task::whereMonth('created_at', now()->month)->count();
        $lastMonth = Task::whereMonth('created_at', now()->subMonth()->month)->count();

        $change = $lastMonth > 0
            ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1)
            : 0;

        return [
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'change_percent' => $change,
        ];
    }

    public function getCompletionRate(?int $userId = null): float
    {
        return $this->taskRepository->getCompletionRate($userId);
    }
}
