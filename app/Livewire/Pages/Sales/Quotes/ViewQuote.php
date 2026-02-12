<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Quotes;

use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Quote;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewQuote extends Component
{
    use HasCurrentTeam;

    public Quote $quote;

    public function mount(Quote $quote): void
    {
        $this->quote = $quote->load(['customer', 'opportunity', 'items']);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.quotes.view-quote');
    }
}
