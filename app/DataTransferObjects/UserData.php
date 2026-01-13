<?php

namespace App\DataTransferObjects;

use App\Enums\UserRole;

class UserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly UserRole $role,
        public readonly string $language,
        public readonly ?string $password = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            role: UserRole::from($data['role'] ?? UserRole::USER->value),
            language: $data['language'] ?? 'en',
            password: $data['password'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role->value,
            'language' => $this->language,
        ];

        if ($this->password !== null) {
            $data['password'] = $this->password;
        }

        return $data;
    }
}
