<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;

class ProjectData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?Carbon $dueDate,
        public readonly ?array $userIds = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            dueDate: isset($data['due_date']) ? Carbon::parse($data['due_date']) : null,
            userIds: $data['users'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'due_date' => $this->dueDate?->toDateString(),
        ], fn($value) => $value !== null);
    }
}
