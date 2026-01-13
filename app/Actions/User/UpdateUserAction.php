<?php

namespace App\Actions\User;

use App\DataTransferObjects\UserData;
use App\Services\UserService;

class UpdateUserAction
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function execute(int $userId, array $data): bool
    {
        $userData = UserData::fromArray($data);

        return $this->userService->updateUser($userId, $userData);
    }
}
