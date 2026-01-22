<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\ProductCategories;

use App\Filament\Resources\ProductCategories\Schemas\ProductCategoryForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\ProductCategory;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditProductCategory extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public ?ProductCategory $productCategory = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?ProductCategory $productCategory = null): void
    {
        $this->productCategory = $productCategory;

        $this->form->fill($productCategory?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return ProductCategoryForm::configure($schema)
            ->statePath('data')
            ->model($this->productCategory ?? ProductCategory::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->productCategory?->exists) {
            $this->productCategory->update($data);
            $message = __('Product category updated successfully.');
        } else {
            $this->productCategory = ProductCategory::create(array_merge($data, ['team_id' => $this->team->id]));
            $this->form->model($this->productCategory)->saveRelationships();
            $message = __('Product category created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.product-categories.view', ['team' => $this->team, 'productCategory' => $this->productCategory]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.products.product-categories.edit-product-category');
    }
}
