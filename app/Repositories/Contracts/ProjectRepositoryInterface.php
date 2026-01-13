<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    public function findById(int $id);

    public function findByUserId(int $userId): Collection;

    public function findAll(): Collection;

    public function create(array $data);

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function countAll(): int;

    public function getProjectsWithTaskCounts(): Collection;

    public function findOverdueProjects(): Collection;
}
