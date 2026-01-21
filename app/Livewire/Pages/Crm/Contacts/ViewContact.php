<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Contacts;

use App\Models\CustomerContact;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewContact extends Component
{
    public CustomerContact $contact;

    public function mount(CustomerContact $contact): void
    {
        $this->contact = $contact->load('customer');
    }

    public function render(): View
    {
        return view('livewire.pages.crm.contacts.view-contact');
    }
}
