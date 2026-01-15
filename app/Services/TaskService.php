<?php

namespace App\Services;

use App\DataTransferObjects\TaskData;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly ProjectUserAssignmentService $projectUserAssignmentService
    ) {}

    public function createTask(TaskData $taskData): Task
    {
        $task = $this->taskRepository->create($taskData->toArray());

        // Assign user to project if needed
        $this->projectUserAssignmentService->assignUserToProject(
            $taskData->userId,
            $taskData->projectId
        );

        return $task;
    }

    public function updateTask(int $taskId, TaskData $taskData): bool
    {
        $updated = $this->taskRepository->update($taskId, $taskData->toArray());

        if ($updated) {
            $this->projectUserAssignmentService->assignUserToProject(
                $taskData->userId,
                $taskData->projectId
            );
        }

        return $updated;
    }

    public function updateTaskStatus(int $taskId, TaskStatus $status): bool
    {
        return $this->taskRepository->update($taskId, [
            'status' => $status
        ]);
    }

    public function deleteTask(int $taskId): bool
    {
        return $this->taskRepository->delete($taskId);
    }

    public function getUrgentTasks(int $limit = 10)
    {
        return $this->taskRepository->findUrgentTasks($limit);
    }

    public function getOverdueTasks()
    {
        return $this->taskRepository->findOverdueTasks();
    }

    public function getTasksByProject(int $projectId)
    {
        return $this->taskRepository->findByProjectId($projectId);
    }

    public function getTasksByUser(int $userId)
    {
        return $this->taskRepository->findByUserId($userId);
    }

    public function getCompletionRate(?int $userId = null): float
    {
        return $this->taskRepository->getCompletionRate($userId);
    }

    public function countTasksByStatus(TaskStatus $status): int
    {
        return $this->taskRepository->countByStatus($status);
    }

    public function isTaskOverdue(Task $task): bool
    {
        return $task->due_date
            && $task->due_date->isPast()
            && $task->status !== TaskStatus::DONE;
    }
}
