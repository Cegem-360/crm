<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Quotes;

use App\Filament\Resources\Quotes\Schemas\QuoteForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Quote;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditQuote extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public ?Quote $quote = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Quote $quote = null): void
    {
        $this->quote = $quote;

        $this->form->fill($quote?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return QuoteForm::configure($schema)
            ->statePath('data')
            ->model($this->quote ?? Quote::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->quote?->exists) {
            $this->quote->update($data);
            $message = __('Quote updated successfully.');
        } else {
            $this->quote = Quote::create(array_merge($data, ['team_id' => $this->team->id]));
            $this->form->model($this->quote)->saveRelationships();
            $message = __('Quote created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.quotes.view', ['team' => $this->team, 'quote' => $this->quote]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.quotes.edit-quote');
    }
}
