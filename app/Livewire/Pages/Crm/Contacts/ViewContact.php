<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Contacts;

use App\Filament\Resources\Contacts\Schemas\ContactInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\CustomerContact;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewContact extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public CustomerContact $contact;

    public function mount(CustomerContact $contact): void
    {
        $this->contact = $contact;
    }

    public function infolist(Schema $schema): Schema
    {
        return ContactInfolist::configure($schema)
            ->record($this->contact)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.crm.contacts.view-contact');
    }
}
