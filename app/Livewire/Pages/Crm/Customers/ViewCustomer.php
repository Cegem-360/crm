<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Customers;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Customers\Pages\ViewCustomer as FilamentViewCustomer;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Customer;
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
final class ViewCustomer extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public Customer $customer;

    #[Url(as: 'relation')]
    public ?string $activeRelationManager = null;

    public function mount(Customer $customer): void
    {
        $this->customer = $customer->load(['company', 'contacts']);
    }

    public function rendering(): void
    {
        $managers = $this->getRelationManagers();

        if (array_key_exists($this->activeRelationManager, $managers)) {
            return;
        }

        $this->activeRelationManager = ($key = array_key_first($managers)) !== null ? (string) $key : null;
    }

    public function relationManagers(Schema $schema): Schema
    {
        $managers = $this->getRelationManagers();

        if ($managers === []) {
            return $schema->components([Group::make()->hidden()]);
        }

        $managerLivewireData = [
            'ownerRecord' => $this->customer,
            'pageClass' => FilamentViewCustomer::class,
        ];

        if (count($managers) > 1) {
            $tabs = collect($managers)
                ->map(function (string|RelationGroup|RelationManagerConfiguration $manager) use ($managerLivewireData): Tab {
                    $normalizedManagerClass = $this->normalizeRelationManagerClass($manager);

                    return $normalizedManagerClass::getTabComponent($this->customer, FilamentViewCustomer::class)
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

    public function render(): View
    {
        return view('livewire.pages.crm.customers.view-customer');
    }

    /**
     * @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration>
     */
    private function getRelationManagers(): array
    {
        $managers = CustomerResource::getRelations();

        return array_filter(
            $managers,
            fn (string|RelationGroup|RelationManagerConfiguration $manager): bool => $this->normalizeRelationManagerClass($manager)::canViewForRecord($this->customer, FilamentViewCustomer::class),
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
