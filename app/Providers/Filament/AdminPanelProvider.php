<?php

namespace App\Providers\Filament;

use App\Config\FilamentTheme;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Users\UserResource;
use App\Filament\UserMenu\LanguageAction;
use App\Filament\Widgets\AllUsersActivityTable;
use App\Filament\Widgets\GlobalProgressWidget;
use App\Filament\Widgets\GlobalStats;
use App\Filament\Widgets\RecentTasksWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\UserProductivityWidget;
use App\Http\Middleware\SetLocale;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->registration(Register::class)
            ->passwordReset(RequestPasswordReset::class)
            ->profile(EditProfile::class)
            ->colors(FilamentTheme::colors())
            ->brandName('TaskFlow Pro')
            ->brandLogo(asset('images/logo.svg'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('images/favicon.png'))
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->topNavigation(false)
            ->font('Inter')
            ->darkMode(true)
            ->defaultThemeMode(ThemeMode::Light)
            ->databaseNotificationsPolling('30s')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->spa()
            ->unsavedChangesAlerts()
            ->databaseTransactions()
            ->userMenuItems($this->getUserMenuItems())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([Dashboard::class])
            ->resources([UserResource::class])
            ->widgets($this->getWidgets())
            ->middleware($this->getMiddleware())
            ->authMiddleware([Authenticate::class]);
    }

    protected function getUserMenuItems(): array
    {
        return [
            Action::make('profile')
                ->label(__('My Profile'))
                ->url(fn (): string => route('filament.admin.auth.profile'))
                ->icon('heroicon-o-user-circle'),
            LanguageAction::make(),
        ];
    }

    protected function getMiddleware(): array
    {
        return [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
            SetLocale::class,
        ];
    }

    protected function getWidgets(): array
    {
        return [
            AllUsersActivityTable::class,
            GlobalProgressWidget::class,
            GlobalStats::class,
            StatsOverviewWidget::class,
            RecentTasksWidget::class,
            UserProductivityWidget::class,
        ];
    }
}
