<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Override;
use UnitEnum;

final class GdprInformation extends Page
{
    protected string $view = 'filament.pages.gdpr-information';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Settings;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 2;

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('GDPR Information');
    }

    #[Override]
    public function getTitle(): string
    {
        return __('GDPR Data Processing Information');
    }
}
