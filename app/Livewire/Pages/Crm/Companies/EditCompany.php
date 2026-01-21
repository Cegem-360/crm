<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Companies;

use App\Filament\Resources\Companies\Schemas\CompanyForm;
use App\Models\Company;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditCompany extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?Company $company = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Company $company = null): void
    {
        $this->company = $company;

        $this->form->fill($company?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema)
            ->statePath('data')
            ->model($this->company ?? Company::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->company?->exists) {
            $this->company->update($data);
            $message = __('Company updated successfully.');
        } else {
            $this->company = Company::create($data);
            $this->form->model($this->company)->saveRelationships();
            $message = __('Company created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.companies.view', $this->company), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.crm.companies.edit-company');
    }
}
