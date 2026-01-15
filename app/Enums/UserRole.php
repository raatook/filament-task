<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel, HasColor, HasIcon
{
    case ADMIN = 'admin';
    case USER = 'user';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => __('Administrator'),
            self::USER => __('User'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::ADMIN => 'danger',
            self::USER => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ADMIN => 'heroicon-o-shield-check',
            self::USER => 'heroicon-o-user',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::ADMIN => __('Full system access with administrative privileges'),
            self::USER => __('Standard user with limited permissions'),
        };
    }


    public function getPermissions(): array
    {
        return match ($this) {
            self::ADMIN => [
                'view_any_user',
                'create_user',
                'update_user',
                'delete_user',
                'view_any_project',
                'create_project',
                'update_project',
                'delete_project',
                'view_any_task',
                'create_task',
                'update_task',
                'delete_task',
                'view_statistics',
            ],
            self::USER => [
                'view_own_tasks',
                'update_own_task_status',
                'view_assigned_projects',
            ],
        };
    }


    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }


    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
}
