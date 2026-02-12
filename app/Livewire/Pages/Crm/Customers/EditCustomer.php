<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Customers;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Customers\Pages\EditCustomer as FilamentEditCustomer;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Customer;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Livewire as LivewireComponent;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditCustomer extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public ?Customer $customer = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    #[Url(as: 'relation')]
    public ?string $activeRelationManager = null;

    public function mount(?Customer $customer = null): void
    {
        $this->customer = $customer;

        $this->form->fill($customer?->attributesToArray() ?? []);
    }

    public function rendering(): void
    {
        if (! $this->customer?->exists) {
            return;
        }

        $managers = $this->getRelationManagers();

        if (array_key_exists($this->activeRelationManager, $managers)) {
            return;
        }

        $this->activeRelationManager = ($key = array_key_first($managers)) !== null ? (string) $key : null;
    }

    public function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema)
            ->statePath('data')
            ->model($this->customer ?? Customer::class);
    }

    public function relationManagers(Schema $schema): Schema
    {
        if (! $this->customer?->exists) {
            return $schema->components([]);
        }

        $managers = $this->getRelationManagers();

        if ($managers === []) {
            return $schema->components([Group::make()->hidden()]);
        }

        $managerLivewireData = [
            'ownerRecord' => $this->customer,
            'pageClass' => FilamentEditCustomer::class,
        ];

        if (count($managers) > 1) {
            $tabs = collect($managers)
                ->map(function (string|RelationGroup|RelationManagerConfiguration $manager) use ($managerLivewireData): Tab {
                    $normalizedManagerClass = $this->normalizeRelationManagerClass($manager);

                    return $normalizedManagerClass::getTabComponent($this->customer, FilamentEditCustomer::class)
                        ->schema(fn (): array => [
                            LivewireComponent::make(
                                $normalizedManagerClass,
                                [
                                    ...$managerLivewireData,
                                    ...(($manager instanceof RelationManagerConfiguration)
                                        ? [...$manager->relationManager::getDefaultProperties(), ...$manager->getProperties()]
                                        : $normalizedManagerClass::getDefaultProperties()),
                                ],
                            )->key($normalizedManagerClass),
                        ]);
                })
                ->all();

            return $schema->components([
                Tabs::make()
                    ->livewireProperty('activeRelationManager')
                    ->contained(false)
                    ->tabs($tabs),
            ]);
        }

        $manager = array_values($managers)[0];
        $normalizedManagerClass = $this->normalizeRelationManagerClass($manager);

        return $schema->components([
            LivewireComponent::make(
                $normalizedManagerClass,
                [
                    ...$managerLivewireData,
                    ...(($manager instanceof RelationManagerConfiguration)
                        ? [...$manager->relationManager::getDefaultProperties(), ...$manager->getProperties()]
                        : $normalizedManagerClass::getDefaultProperties()),
                ],
            )->key($normalizedManagerClass),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->customer?->exists) {
            $this->customer->update($data);
            $message = __('Customer updated successfully.');
        } else {
            $this->customer = Customer::query()->create(array_merge($data, ['team_id' => $this->team->id]));
            $this->form->model($this->customer)->saveRelationships();
            $message = __('Customer created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.customers.view', ['team' => $this->team, 'customer' => $this->customer]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.crm.customers.edit-customer');
    }

    /**
     * @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration>
     */
    private function getRelationManagers(): array
    {
        if (! $this->customer?->exists) {
            return [];
        }

        $managers = CustomerResource::getRelations();

        return array_filter(
            $managers,
            fn (string|RelationGroup|RelationManagerConfiguration $manager): bool => $this->normalizeRelationManagerClass($manager)::canViewForRecord($this->customer, FilamentEditCustomer::class),
        );
    }

    /**
     * @param  class-string<RelationManager> | RelationManagerConfiguration  $manager
     * @return class-string<RelationManager>
     */
    private function normalizeRelationManagerClass(string|RelationManagerConfiguration $manager): string
    {
        if ($manager instanceof RelationManagerConfiguration) {
            return $manager->relationManager;
        }

        return $manager;
    }
}
