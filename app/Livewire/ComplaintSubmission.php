<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\ComplaintSeverity;
use App\Enums\ComplaintStatus;
use App\Enums\CustomerType;
use App\Enums\Role;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\User;
use App\Notifications\NewComplaintNotification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Validate;
use Livewire\Component;

final class ComplaintSubmission extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string|min:10')]
    public string $description = '';

    #[Validate('nullable|string|max:255')]
    public string $order_number = '';

    public bool $submitted = false;

    public function submit(): void
    {
        $this->validate();

        $existingContact = CustomerContact::query()
            ->where('email', $this->email)
            ->first();

        if ($existingContact) {
            $customer = $existingContact->customer;
        } else {
            $customer = Customer::query()->create([
                'unique_identifier' => 'GUEST-'.now()->format('YmdHis'),
                'name' => $this->name,
                'phone' => $this->phone,
                'type' => CustomerType::Company,
                'is_active' => true,
            ]);

            $customer->contacts()->create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'is_primary' => true,
            ]);
        }

        $complaint = Complaint::query()->create([
            'customer_id' => $customer->id,
            'title' => $this->title,
            'description' => $this->description,
            'severity' => ComplaintSeverity::Medium,
            'status' => ComplaintStatus::Open,
            'reported_at' => now(),
        ]);

        $this->notifyAdmins($complaint);

        $this->submitted = true;

        $this->reset(['name', 'email', 'phone', 'title', 'description', 'order_number']);
    }

    public function render(): Factory|View
    {
        return view('livewire.complaint-submission');
    }

    private function notifyAdmins(Complaint $complaint): void
    {
        $roleNames = [Role::Admin->value, Role::Manager->value];

        $adminsAndManagers = User::query()
            ->whereHas('roles', fn ($query) => $query->whereIn('name', $roleNames))
            ->get();

        if ($adminsAndManagers->isNotEmpty()) {
            Notification::send($adminsAndManagers, new NewComplaintNotification($complaint));
        }
    }
}
