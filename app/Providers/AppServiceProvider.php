<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Commands\FileGenerators\Resources\ResourceClassGenerator;
use App\Models\BugReport;
use App\Models\Company;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\Interaction;
use App\Models\Invoice;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Task;
use App\Models\User;
use Filament\Commands\FileGenerators\Resources\ResourceClassGenerator as BaseResourceClassGenerator;
use Filament\Forms\Components\Field;
use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentTimezone::set('Europe/Budapest');

        Field::configureUsing(static fn (Field $field): Field => $field->translateLabel());

        $this->app->bind(BaseResourceClassGenerator::class, ResourceClassGenerator::class);
        Relation::enforceMorphMap([
            'bug_report' => BugReport::class,
            'company' => Company::class,
            'complaint' => Complaint::class,
            'customer' => Customer::class,
            'customer_contact' => CustomerContact::class,
            'interaction' => Interaction::class,
            'invoice' => Invoice::class,
            'opportunity' => Opportunity::class,
            'order' => Order::class,
            'product' => Product::class,
            'quote' => Quote::class,
            'task' => Task::class,
            'user' => User::class,
        ]);
    }
}
