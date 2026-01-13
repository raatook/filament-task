<?php

namespace App\Filament\Widgets;

use App\Services\ProjectService;
use App\Services\TaskStatisticsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GlobalStats extends BaseWidget
{
    protected function getStats(): array
    {
        $statisticsService = app(TaskStatisticsService::class);
        $projectService = app(ProjectService::class);

        $stats = $statisticsService->getGlobalStatistics();
        $projectCount = $projectService->getAllProjects()->count();

        return [
            Stat::make(__('Active Projects'), $projectCount)
                ->description(__('Total projects in progress'))
                ->descriptionIcon('heroicon-o-rectangle-stack'),

            Stat::make(__('Tasks In Progress'), $stats['in_progress'])
                ->description(__('Currently active tasks'))
                ->descriptionIcon('heroicon-o-bolt'),

            Stat::make(__('Urgent Tasks'), $stats['urgent'])
                ->description(__('Priority tasks'))
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),

            Stat::make(__('Overdue Tasks'), $stats['overdue'])
                ->description(__('Past deadline tasks'))
                ->descriptionIcon('heroicon-o-clock')
                ->color('danger'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
