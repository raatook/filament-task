<?php

namespace App\DataTransferObjects;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Carbon\Carbon;

class TaskData
{
    public function __construct(
        public readonly int $projectId,
        public readonly int $userId,
        public readonly string $title,
        public readonly ?string $description,
        public readonly TaskStatus $status,
        public readonly TaskPriority $priority,
        public readonly ?Carbon $dueDate
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            projectId: $data['project_id'],
            userId: $data['user_id'],
            title: $data['title'],
            description: $data['description'] ?? null,
            status: isset($data['status'])
                ? (is_string($data['status']) ? TaskStatus::from($data['status']) : $data['status'])
                : TaskStatus::PENDING,
            priority: isset($data['priority'])
                ? (is_int($data['priority']) ? TaskPriority::from($data['priority']) : $data['priority'])
                : TaskPriority::LOW,
            dueDate: isset($data['due_date']) ? Carbon::parse($data['due_date']) : null
        );
    }

    public function toArray(): array
    {
        return [
            'project_id' => $this->projectId,
            'user_id' => $this->userId,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'due_date' => $this->dueDate?->toDateString(),
        ];
    }
}
