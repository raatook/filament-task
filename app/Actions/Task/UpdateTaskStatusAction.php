<?php

namespace App\Actions\Task;

use App\Enums\TaskStatus;
use App\Services\TaskService;

class UpdateTaskStatusAction
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    public function execute(int $taskId, TaskStatus $status): bool
    {
        return $this->taskService->updateTaskStatus($taskId, $status);
    }
}
