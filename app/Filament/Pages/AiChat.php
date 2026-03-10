<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Override;
use UnitEnum;

final class AiChat extends Page
{
    protected string $view = 'filament.pages.ai-chat';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Support;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static ?int $navigationSort = 4;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('AI Assistant');
    }

    #[Override]
    public function getTitle(): string
    {
        return __('AI Assistant');
    }

    #[Override]
    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }
}
