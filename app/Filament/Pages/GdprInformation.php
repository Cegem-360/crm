<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use Filament\Pages\Page;
use Override;
use UnitEnum;

final class GdprInformation extends Page
{
    protected string $view = 'filament.pages.gdpr-information';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Settings;

    protected static ?int $navigationSort = 50;

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
