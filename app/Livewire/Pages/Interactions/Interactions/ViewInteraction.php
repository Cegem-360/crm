<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions\Interactions;

use App\Models\Interaction;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewInteraction extends Component
{
    public Interaction $interaction;

    public function mount(Interaction $interaction): void
    {
        $this->interaction = $interaction->load(['customer', 'contact', 'user', 'emailTemplate']);
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.interactions.view-interaction');
    }
}
