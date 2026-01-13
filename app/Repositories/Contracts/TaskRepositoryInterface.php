<?php

namespace App\Repositories\Contracts;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function findById(int $id);

    public function findByUserId(int $userId): Collection;

    public function findByProjectId(int $projectId): Collection;

    public function findByStatus(TaskStatus $status): Collection;

    public function findUrgentTasks(int $limit = 10): Collection;

    public function findOverdueTasks(): Collection;

    public function create(array $data);

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function countByStatus(TaskStatus $status): int;

    public function getCompletionRate(?int $userId = null): float;
}
