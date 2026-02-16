<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Support\Complaints;

use App\Filament\Resources\Complaints\Schemas\ComplaintInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Complaint;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewComplaint extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Complaint $complaint;

    public function mount(Complaint $complaint): void
    {
        $this->complaint = $complaint;
    }

    public function infolist(Schema $schema): Schema
    {
        return ComplaintInfolist::configure($schema)
            ->record($this->complaint)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.support.complaints.view-complaint');
    }
}
