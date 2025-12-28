<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;

class GlobalProgressWidget extends ChartWidget
{
    protected int|string|array $columnSpan = 'full';

    public function getHeading(): string
    {
        return __('Global Task Progress');
    }

    protected function getData(): array
    {
        $statusCounts = Task::withoutGlobalScopes()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $pending = $statusCounts['pending'] ?? 0;
        $inProgress = $statusCounts['in_progress'] ?? 0;
        $done = $statusCounts['done'] ?? 0;

        return [
            'datasets' => [
                [
                    'label' => __('Tasks by Status'),
                    'data' => [$pending, $inProgress, $done],
                    'backgroundColor' => [
                        'rgb(156, 163, 175)',
                        'rgb(251, 191, 36)',
                        'rgb(34, 197, 94)',
                    ],
                ],
            ],
            'labels' => [
                __('Pending').' ('.$pending.')',
                __('In Progress').' ('.$inProgress.')',
                __('Done').' ('.$done.')',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public static function canView(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
