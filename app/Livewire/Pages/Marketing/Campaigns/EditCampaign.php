<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Marketing\Campaigns;

use App\Filament\Resources\Campaigns\Schemas\CampaignForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Campaign;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditCampaign extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public ?Campaign $campaign = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Campaign $campaign = null): void
    {
        $this->campaign = $campaign;

        $this->form->fill($campaign?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return CampaignForm::configure($schema)
            ->statePath('data')
            ->model($this->campaign ?? Campaign::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->campaign?->exists) {
            $this->campaign->update($data);
            $message = __('Campaign updated successfully.');
        } else {
            $this->campaign = Campaign::query()->create(array_merge($data, [
                'team_id' => $this->team->id,
                'created_by' => $data['created_by'] ?? Auth::id(),
            ]));
            $this->form->model($this->campaign)->saveRelationships();
            $message = __('Campaign created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.campaigns.view', ['team' => $this->team, 'campaign' => $this->campaign]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.marketing.campaigns.edit-campaign');
    }
}
