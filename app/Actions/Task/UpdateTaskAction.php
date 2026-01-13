<?php

namespace App\Actions\Task;

use App\DataTransferObjects\TaskData;
use App\Services\TaskService;

class UpdateTaskAction
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    public function execute(int $taskId, array $data): bool
    {
        $taskData = TaskData::fromArray($data);

        return $this->taskService->updateTask($taskId, $taskData);
    }
}
