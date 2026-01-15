<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TaskStatus: string implements HasLabel, HasColor, HasIcon
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::IN_PROGRESS => __('In Progress'),
            self::DONE => __('Done'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::IN_PROGRESS => 'warning',
            self::DONE => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-clock',
            self::IN_PROGRESS => 'heroicon-o-arrow-path',
            self::DONE => 'heroicon-o-check-circle',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::PENDING => __('Task is waiting to be started'),
            self::IN_PROGRESS => __('Task is currently being worked on'),
            self::DONE => __('Task has been completed'),
        };
    }


    public function isFinal(): bool
    {
        return $this === self::DONE;
    }

    public function getNextStatuses(): array
    {
        return match ($this) {
            self::PENDING => [self::IN_PROGRESS, self::DONE],
            self::IN_PROGRESS => [self::DONE, self::PENDING],
            self::DONE => [],
        };
    }
}
