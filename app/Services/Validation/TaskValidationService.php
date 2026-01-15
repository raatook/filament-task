<?php

namespace App\Services\Validation;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class TaskValidationService
{
    public function validateTaskData(array $data): array
    {
        $validator = Validator::make($data, [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', new Enum(TaskStatus::class)],
            'priority' => ['required', new Enum(TaskPriority::class)],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function validateStatusUpdate(array $data): array
    {
        $validator = Validator::make($data, [
            'status' => ['required', new Enum(TaskStatus::class)],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function canUserEditTask(int $userId, int $taskUserId, bool $isAdmin): bool
    {
        return $isAdmin || $userId === $taskUserId;
    }

    public function canUserDeleteTask(bool $isAdmin): bool
    {
        return $isAdmin;
    }

    public function isValidStatusTransition(TaskStatus $currentStatus, TaskStatus $newStatus): bool
    {
        $allowedTransitions = $currentStatus->getNextStatuses();

        if (empty($allowedTransitions)) {
            return $currentStatus === $newStatus;
        }

        return in_array($newStatus, $allowedTransitions, true);
    }
}
