<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadOpportunities\Pages;

use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\Customers\Actions\GenerateQuoteAction;
use App\Filament\Resources\Customers\Actions\ViewGeneratedQuoteAction;
use App\Filament\Resources\LeadOpportunities\LeadOpportunitiesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditLeadOpportunity extends EditRecord
{
    use ManagesCustomFields;

    protected static string $resource = LeadOpportunitiesResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            GenerateQuoteAction::make(),
            ViewGeneratedQuoteAction::make(),
            DeleteAction::make(),
            ViewAction::make(),
        ];
    }
}
