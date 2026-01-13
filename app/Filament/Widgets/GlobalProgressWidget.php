<?php

namespace App\Filament\Widgets;

use App\Services\TaskStatisticsService;
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
        $statisticsService = app(TaskStatisticsService::class);
        $stats = $statisticsService->getGlobalStatistics();

        return [
            'datasets' => [
                [
                    'label' => __('Tasks by Status'),
                    'data' => [$stats['pending'], $stats['in_progress'], $stats['done']],
                    'backgroundColor' => [
                        'rgb(156, 163, 175)',
                        'rgb(251, 191, 36)',
                        'rgb(34, 197, 94)',
                    ],
                ],
            ],
            'labels' => [
                __('Pending').' ('.$stats['pending'].')',
                __('In Progress').' ('.$stats['in_progress'].')',
                __('Done').' ('.$stats['done'].')',
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
