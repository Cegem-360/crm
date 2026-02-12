<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\Discounts;

use App\Filament\Resources\Discounts\Schemas\DiscountForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Discount;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditDiscount extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public ?Discount $discount = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Discount $discount = null): void
    {
        $this->discount = $discount;

        $this->form->fill($discount?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return DiscountForm::configure($schema)
            ->statePath('data')
            ->model($this->discount ?? Discount::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->discount?->exists) {
            $this->discount->update($data);
            $message = __('Discount updated successfully.');
        } else {
            $this->discount = Discount::query()->create(array_merge($data, ['team_id' => $this->team->id]));
            $this->form->model($this->discount)->saveRelationships();
            $message = __('Discount created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.discounts.view', ['team' => $this->team, 'discount' => $this->discount]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.products.discounts.edit-discount');
    }
}
