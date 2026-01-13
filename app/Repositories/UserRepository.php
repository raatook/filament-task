<?php

namespace App\Repositories;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id)
    {
        return User::find($id);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function findByRole(UserRole $role): Collection
    {
        return User::where('role', $role->value)->get();
    }

    public function findAll(): Collection
    {
        return User::all();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = $this->findById($id);

        if (!$user) {
            return false;
        }

        return $user->update($data);
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);

        if (!$user) {
            return false;
        }

        return $user->delete();
    }

    public function countByRole(UserRole $role): int
    {
        return User::where('role', $role->value)->count();
    }

    public function getUsersWithTaskStatistics(): Collection
    {
        return User::where('role', UserRole::USER->value)
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn($query) =>
                    $query->where('status', 'done'),
                'tasks as in_progress_tasks_count' => fn($query) =>
                    $query->where('status', 'in_progress'),
                'tasks as pending_tasks_count' => fn($query) =>
                    $query->where('status', 'pending'),
                'tasks as overdue_tasks_count' => fn($query) =>
                    $query->where('status', '!=', 'done')
                        ->where('due_date', '<', now()),
            ])
            ->get();
    }
}
