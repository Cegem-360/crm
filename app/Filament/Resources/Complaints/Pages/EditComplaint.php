<?php

declare(strict_types=1);

namespace App\Filament\Resources\Complaints\Pages;

use Override;
use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\Complaints\ComplaintResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditComplaint extends EditRecord
{
    use ManagesCustomFields;

    protected static string $resource = ComplaintResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
