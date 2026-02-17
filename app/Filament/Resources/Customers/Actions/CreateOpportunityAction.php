<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Actions;

use App\Filament\Resources\LeadOpportunities\LeadOpportunitiesResource;
use App\Models\Customer;
use Filament\Actions\Action;

final class CreateOpportunityAction
{
    public static function make(): Action
    {
        return Action::make('create_opportunity')
            ->label(__('New Opportunity'))
            ->icon('heroicon-o-plus-circle')
            ->color('success')
            ->url(static fn (Customer $record): string => LeadOpportunitiesResource::getUrl('create', [
                'customer_id' => $record->getKey(),
            ]));
    }
}
