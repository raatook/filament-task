<?php

namespace App\Config;

use Filament\Support\Colors\Color;

class FilamentTheme
{
    public static function colors(): array
    {
        return [
            'primary' => Color::Blue,
            'danger' => Color::Red,
            'gray' => Color::Slate,
            'info' => Color::Sky,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
        ];
    }
}
