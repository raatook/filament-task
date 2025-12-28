<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin();

        $totalTasks = $isAdmin ? Task::withoutGlobalScopes()->count() : Task::count();
        $completedTasks = $isAdmin
            ? Task::withoutGlobalScopes()->where('status', 'done')->count()
            : Task::where('status', 'done')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        $tasksThisMonth = $isAdmin
            ? Task::withoutGlobalScopes()->whereMonth('created_at', now()->month)->count()
            : Task::whereMonth('created_at', now()->month)->count();
        $tasksLastMonth = $isAdmin
            ? Task::withoutGlobalScopes()->whereMonth('created_at', now()->subMonth()->month)->count()
            : Task::whereMonth('created_at', now()->subMonth()->month)->count();
        $taskChange = $tasksLastMonth > 0 ? round((($tasksThisMonth - $tasksLastMonth) / $tasksLastMonth) * 100, 1) : 0;

        $projectCount = $isAdmin ? Project::withoutGlobalScopes()->count() : Project::count();
        $pendingTasks = $isAdmin
            ? Task::withoutGlobalScopes()->where('status', 'pending')->count()
            : Task::where('status', 'pending')->count();

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

            Stat::make(__('Tasks This Month'), $tasksThisMonth)
                ->description($taskChange >= 0 ? "{$taskChange}% ".__('increase') : "{$taskChange}% ".__('decrease'))
                ->descriptionIcon($taskChange >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($taskChange >= 0 ? 'success' : 'danger'),

            Stat::make(__('Pending Tasks'), $pendingTasks)
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
