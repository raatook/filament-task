<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TaskPriority: int implements HasLabel, HasColor, HasIcon
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;
    case URGENT = 4;
    case CRITICAL = 5;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LOW => __('Low'),
            self::MEDIUM => __('Medium'),
            self::HIGH => __('High'),
            self::URGENT => __('Urgent'),
            self::CRITICAL => __('Critical'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::LOW => 'gray',
            self::MEDIUM => 'info',
            self::HIGH => 'warning',
            self::URGENT => 'danger',
            self::CRITICAL => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::LOW => 'heroicon-o-arrow-down',
            self::MEDIUM => 'heroicon-o-minus',
            self::HIGH => 'heroicon-o-arrow-up',
            self::URGENT => 'heroicon-o-exclamation-circle',
            self::CRITICAL => 'heroicon-o-exclamation-triangle',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::LOW => __('Can be done when time permits'),
            self::MEDIUM => __('Normal priority task'),
            self::HIGH => __('Should be done soon'),
            self::URGENT => __('Needs immediate attention'),
            self::CRITICAL => __('Critical issue requiring immediate action'),
        };
    }


    public function isUrgent(): bool
    {
        return in_array($this, [self::URGENT, self::CRITICAL]);
    }


    public function isLow(): bool
    {
        return in_array($this, [self::LOW, self::MEDIUM]);
    }


    public function getWeight(): int
    {
        return $this->value;
    }

    public function isHigherThan(TaskPriority $other): bool
    {
        return $this->value > $other->value;
    }
}
