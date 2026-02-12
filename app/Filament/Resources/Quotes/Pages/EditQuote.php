<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quotes\Pages;

use Override;
use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\Quotes\QuoteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

final class EditQuote extends EditRecord
{
    use ManagesCustomFields;

    protected static string $resource = QuoteResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
