<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Support\Complaints;

use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Complaint;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewComplaint extends Component
{
    use HasCurrentTeam;

    public Complaint $complaint;

    public function mount(Complaint $complaint): void
    {
        $this->complaint = $complaint->load(['customer', 'order', 'assignedUser', 'reporter', 'escalations']);
    }

    public function render(): View
    {
        return view('livewire.pages.support.complaints.view-complaint');
    }
}
