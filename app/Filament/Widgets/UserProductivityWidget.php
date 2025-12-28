<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserProductivityWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $users = User::where('role', 'user')
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => function ($query) {
                    $query->where('status', 'done');
                },
                'tasks as in_progress_tasks_count' => function ($query) {
                    $query->where('status', 'in_progress');
                },
            ])
            ->get()
            ->map(function ($user) {
                $completionRate = $user->tasks_count > 0
                    ? round(($user->completed_tasks_count / $user->tasks_count) * 100, 1)
                    : 0;

                return [
                    'name' => $user->name,
                    'total_tasks' => $user->tasks_count,
                    'completed' => $user->completed_tasks_count,
                    'in_progress' => $user->in_progress_tasks_count,
                    'completion_rate' => $completionRate,
                ];
            })
            ->sortByDesc('total_tasks')
            ->take(3);

        $stats = [];

        foreach ($users as $index => $userData) {
            $emoji = match ($index) {
                0 => '🏆',
                1 => '🥈',
                2 => '🥉',
                default => '👤',
            };

            $stats[] = Stat::make($emoji.' '.$userData['name'], $userData['total_tasks'].' '.__('Tasks'))
                ->description(__('Completed').': '.$userData['completed'].' ('.$userData['completion_rate'].'%) | '.__('In Progress').': '.$userData['in_progress'])
                ->descriptionIcon('heroicon-o-user')
                ->color($userData['completion_rate'] >= 70 ? 'success' : ($userData['completion_rate'] >= 40 ? 'warning' : 'danger'));
        }

        return $stats;
    }

    public static function canView(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
