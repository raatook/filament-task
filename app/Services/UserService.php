<?php

namespace App\Services;

use App\DataTransferObjects\UserData;
use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function createUser(UserData $userData): User
    {
        $data = $userData->toArray();

        if ($userData->password) {
            $data['password'] = Hash::make($userData->password);
        }

        return $this->userRepository->create($data);
    }

    public function updateUser(int $userId, UserData $userData): bool
    {
        $data = $userData->toArray();

        if ($userData->password) {
            $data['password'] = Hash::make($userData->password);
        }

        return $this->userRepository->update($userId, $data);
    }

    public function deleteUser(int $userId): bool
    {
        return $this->userRepository->delete($userId);
    }

    public function getUserById(int $userId)
    {
        return $this->userRepository->findById($userId);
    }

    public function getUserByEmail(string $email)
    {
        return $this->userRepository->findByEmail($email);
    }

    public function getAllUsers()
    {
        return $this->userRepository->findAll();
    }

    public function getUsersByRole(UserRole $role)
    {
        return $this->userRepository->findByRole($role);
    }

    public function getUsersWithTaskStatistics()
    {
        return $this->userRepository->getUsersWithTaskStatistics();
    }

    public function countUsersByRole(UserRole $role): int
    {
        return $this->userRepository->countByRole($role);
    }
}
