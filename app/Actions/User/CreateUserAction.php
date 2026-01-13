<?php

namespace App\Actions\User;

use App\DataTransferObjects\UserData;
use App\Models\User;
use App\Services\UserService;

class CreateUserAction
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function execute(array $data): User
    {
        $userData = UserData::fromArray($data);

        return $this->userService->createUser($userData);
    }
}
