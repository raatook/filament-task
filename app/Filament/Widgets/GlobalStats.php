<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GlobalStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Projets actifs', Project::count())
                ->description('Total des projets en cours')
                ->descriptionIcon('heroicon-o-rectangle-stack'),

            Stat::make('Tâches en cours', Task::where('status', 'in_progress')->count())
                ->description('Tâches actuellement actives')
                ->descriptionIcon('heroicon-o-bolt'),

            Stat::make('Urgences', Task::whereIn('priority', [4, 5])->count())
                ->description('Tâches prioritaires')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),

            Stat::make('En retard', Task::where('due_date', '<', now())->where('status', '!=', 'done')->count())
                ->description('Tâches dépassées')
                ->descriptionIcon('heroicon-o-clock')
                ->color('danger'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
