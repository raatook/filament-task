<?php

namespace App\Actions\Task;

use App\DataTransferObjects\TaskData;
use App\Models\Task;
use App\Services\TaskService;

class CreateTaskAction
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    public function execute(array $data): Task
    {
        $taskData = TaskData::fromArray($data);

        return $this->taskService->createTask($taskData);
    }
}
