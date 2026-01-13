<?php

namespace App\Repositories\Contracts;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function findById(int $id);

    public function findByEmail(string $email);

    public function findByRole(UserRole $role): Collection;

    public function findAll(): Collection;

    public function create(array $data);

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function countByRole(UserRole $role): int;

    public function getUsersWithTaskStatistics(): Collection;
}
