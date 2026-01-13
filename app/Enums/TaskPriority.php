<?php

namespace App\Enums;

enum TaskPriority: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;
    case URGENT = 4;
    case CRITICAL = 5;

    public function label(): string
    {
        return match ($this) {
            self::LOW => __('Low'),
            self::MEDIUM => __('Medium'),
            self::HIGH => __('High'),
            self::URGENT => __('Urgent'),
            self::CRITICAL => __('Critical'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LOW, self::MEDIUM => 'gray',
            self::HIGH => 'warning',
            self::URGENT, self::CRITICAL => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::URGENT, self::CRITICAL => 'heroicon-o-arrow-up',
            self::HIGH => 'heroicon-o-minus',
            self::LOW, self::MEDIUM => 'heroicon-o-arrow-down',
        };
    }

    public function isUrgent(): bool
    {
        return in_array($this, [self::URGENT, self::CRITICAL]);
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($priority) => [
                $priority->value => $priority->value . ' - ' . $priority->label()
            ])
            ->toArray();
    }
}
