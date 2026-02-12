<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadOpportunities\Pages;

use Override;
use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\LeadOpportunities\LeadOpportunitiesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditLeadOpportunity extends EditRecord
{
    use ManagesCustomFields;

    protected static string $resource = LeadOpportunitiesResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
