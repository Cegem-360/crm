<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Enums\NavigationGroup;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\EditTeamProfile;
use App\Filament\Pages\RegisterTeam;
use App\Http\Middleware\ApplyTenantScopes;
use App\Http\Middleware\SetLocale;
use App\Models\Team;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup as FilamentNavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

final class AdminPanelServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->font('Figtree')
            ->sidebarFullyCollapsibleOnDesktop()
            ->sidebarWidth('15rem')
            ->collapsibleNavigationGroups(false)
            ->navigationGroups([
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::Customers->getLabel())
                    ->extraSidebarAttributes(['class' => 'fi-nav-group-customers']),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::Sales->getLabel())
                    ->extraSidebarAttributes(['class' => 'fi-nav-group-sales']),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::Products->getLabel())
                    ->extraSidebarAttributes(['class' => 'fi-nav-group-products']),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::Marketing->getLabel())
                    ->extraSidebarAttributes(['class' => 'fi-nav-group-marketing']),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::Activities->getLabel())
                    ->extraSidebarAttributes(['class' => 'fi-nav-group-activities']),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::Support->getLabel())
                    ->extraSidebarAttributes(['class' => 'fi-nav-group-support']),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::Reports->getLabel())
                    ->extraSidebarAttributes(['class' => 'fi-nav-group-reports']),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::Settings->getLabel())
                    ->extraSidebarAttributes(['class' => 'fi-nav-group-settings']),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::System->getLabel())
                    ->extraSidebarAttributes(['class' => 'fi-nav-group-system']),
            ])
            ->login(Login::class)
            ->userMenuItems([
                'profile' => fn (Action $action): Action => $action
                    ->url('https://cegem360.eu/admin/profile', shouldOpenInNewTab: true),
            ])
            ->tenant(Team::class, slugAttribute: 'slug')
            ->tenantRegistration(RegisterTeam::class)
            ->tenantProfile(EditTeamProfile::class)
            ->tenantMenu(fn (): bool => Auth::check() && (Auth::user()->isAdmin() || Auth::user()->teams()->count() > 1))
            ->tenantMiddleware([
                ApplyTenantScopes::class,
            ], isPersistent: true)
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('3rem')
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): View => view('filament.topbar-items'),
            )
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentApexChartsPlugin::make(),
            ])
            ->spa();
    }
}
