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
            ->navigationGroups(
                collect(NavigationGroup::cases())
                    ->mapWithKeys(fn (NavigationGroup $group): array => [
                        $group->name => FilamentNavigationGroup::make()
                            ->label(fn (): string => $group->getLabel())
                            ->extraSidebarAttributes(['class' => 'fi-nav-group-'.str($group->name)->kebab()]),
                    ])
                    ->all(),
            )
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
            ->renderHook(
                PanelsRenderHook::SCRIPTS_AFTER,
                fn (): View => view('filament.sidebar-transition-script'),
            )
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
