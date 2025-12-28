<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AllUsersActivityTable;
use App\Filament\Widgets\GlobalProgressWidget;
use App\Filament\Widgets\GlobalStats;
use App\Filament\Widgets\RecentTasksWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\UserProductivityWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public static function getNavigationLabel(): string
    {
        return __('Dashboard');
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    public function getWidgets(): array
    {
        return [
            GlobalProgressWidget::class,
            RecentTasksWidget::class,
            AllUsersActivityTable::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 3,
            'lg' => 3,
            'xl' => 4,
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            UserProductivityWidget::class,
            GlobalStats::class,
            StatsOverviewWidget::class,
        ];
    }
}
