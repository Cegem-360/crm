<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadOpportunities\Pages;

use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\LeadOpportunities\LeadOpportunitiesResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

final class CreateLeadOpportunity extends CreateRecord
{
    use ManagesCustomFields;

    protected static string $resource = LeadOpportunitiesResource::class;

    #[Override]
    public function mount(): void
    {
        parent::mount();

        $customerId = $this->getCustomerIdFromRequest();

        if ($customerId !== null) {
            $this->form->fill([
                ...$this->form->getRawState(),
                'customer_id' => $customerId,
            ]);
        }
    }

    #[Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['customer_id'] ??= $this->getCustomerIdFromRequest();

        return $data;
    }

    private function getCustomerIdFromRequest(): ?int
    {
        if (! request()->has('customer_id')) {
            return null;
        }

        return (int) request()->query('customer_id');
    }
}
