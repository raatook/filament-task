<?php

namespace App\Services\Validation;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
            'status' => ['required', Rule::in(array_column(TaskStatus::cases(), 'value'))],
            'priority' => ['required', Rule::in(array_column(TaskPriority::cases(), 'value'))],
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
            'status' => ['required', Rule::in(array_column(TaskStatus::cases(), 'value'))],
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
}
