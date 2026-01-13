<?php

namespace App\Filament\Widgets;

use App\Services\TaskStatisticsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserProductivityWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $statisticsService = app(TaskStatisticsService::class);

        $usersData = $statisticsService->getAllUsersProductivity()
            ->sortByDesc('total_tasks')
            ->take(3)
            ->values();

        $stats = [];

        foreach ($usersData as $index => $userData) {
            $emoji = match ($index) {
                0 => '🏆',
                1 => '🥈',
                2 => '🥉',
                default => '👤',
            };

            $stats[] = Stat::make(
                $emoji.' '.$userData['user']->name,
                $userData['total_tasks'].' '.__('Tasks')
            )
                ->description(
                    __('Completed').': '.$userData['completed'].' ('.
                    $userData['completion_rate'].'%) | '.
                    __('In Progress').': '.$userData['in_progress']
                )
                ->descriptionIcon('heroicon-o-user')
                ->color(
                    $userData['completion_rate'] >= 70
                        ? 'success'
                        : ($userData['completion_rate'] >= 40 ? 'warning' : 'danger')
                );
        }

        return $stats;
    }

    public static function canView(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
