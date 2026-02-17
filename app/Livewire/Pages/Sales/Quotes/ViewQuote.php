<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Quotes;

use App\Filament\Resources\Customers\Actions\GenerateOrderAction;
use App\Filament\Resources\Quotes\Schemas\QuoteInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Quote;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewQuote extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Quote $quote;

    public function mount(Quote $quote): void
    {
        $this->quote = $quote;
    }

    public function infolist(Schema $schema): Schema
    {
        return QuoteInfolist::configure($schema)
            ->record($this->quote)
            ->columns(2);
    }

    public function generateOrderAction(): Action
    {
        return GenerateOrderAction::make()
            ->record($this->quote);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.quotes.view-quote');
    }
}
