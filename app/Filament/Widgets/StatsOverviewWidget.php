<?php

namespace App\Filament\Widgets;

use App\Services\ProjectService;
use App\Services\TaskStatisticsService;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $statisticsService = app(TaskStatisticsService::class);
        $projectService = app(ProjectService::class);

        $user = auth()->user();
        $isAdmin = $user->isAdmin();

        $userId = $isAdmin ? null : $user->id;
        $completionRate = $statisticsService->getCompletionRate($userId);
        $stats = $isAdmin
            ? $statisticsService->getGlobalStatistics()
            : $statisticsService->getUserStatistics($user->id);

        $projectCount = $isAdmin
            ? $projectService->getAllProjects()->count()
            : $projectService->getUserProjects($user->id)->count();

        $trend = $statisticsService->getMonthlyTaskTrend();
        $totalTasks = $stats['total'] ?? ($stats['pending'] + $stats['in_progress'] + $stats['done']);
        $completedTasks = $stats['done'];

        return [
            Stat::make(__('Total Projects'), $projectCount)
                ->description($isAdmin ? __('All projects') : __('Your projects'))
                ->descriptionIcon('heroicon-o-folder')
                ->color('primary'),

            Stat::make(__('Task Completion'), $completionRate.'%')
                ->description("{$completedTasks} ".__('of')." {$totalTasks} ".__('tasks completed'))
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([30, 45, 60, 55, 70, 65, 75, $completionRate]),

            Stat::make(__('Tasks This Month'), $trend['this_month'])
                ->description(
                    $trend['change_percent'] >= 0
                        ? "{$trend['change_percent']}% ".__('increase')
                        : abs($trend['change_percent'])."% ".__('decrease')
                )
                ->descriptionIcon(
                    $trend['change_percent'] >= 0
                        ? 'heroicon-o-arrow-trending-up'
                        : 'heroicon-o-arrow-trending-down'
                )
                ->color($trend['change_percent'] >= 0 ? 'success' : 'danger'),

            Stat::make(__('Pending Tasks'), $stats['pending'])
                ->description(__('Waiting to start'))
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->isUser() ?? false;
    }
}
