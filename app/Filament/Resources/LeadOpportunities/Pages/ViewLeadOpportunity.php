<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadOpportunities\Pages;

use App\Filament\Resources\LeadOpportunities\LeadOpportunitiesResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Override;

final class ViewLeadOpportunity extends ViewRecord
{
    protected static string $resource = LeadOpportunitiesResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
