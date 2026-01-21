<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\Products;

use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Models\Product;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditProduct extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?Product $product = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Product $product = null): void
    {
        $this->product = $product;

        $this->form->fill($product?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema)
            ->statePath('data')
            ->model($this->product ?? Product::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->product?->exists) {
            $this->product->update($data);
            $message = __('Product updated successfully.');
        } else {
            $this->product = Product::create($data);
            $this->form->model($this->product)->saveRelationships();
            $message = __('Product created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.products.view', $this->product), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.products.products.edit-product');
    }
}
