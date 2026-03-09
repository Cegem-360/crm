<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Commands\FileGenerators\Resources\ResourceClassGenerator;
use App\Models\BugReport;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\CustomerConsent;
use App\Models\CustomerContact;
use App\Models\EmailTemplate;
use App\Models\GoogleCalendarToken;
use App\Models\Interaction;
use App\Models\Invoice;
use App\Models\LeadScore;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quote;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Commands\FileGenerators\Resources\ResourceClassGenerator as BaseResourceClassGenerator;
use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Support\Facades\FilamentTimezone;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
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
        Column::configureUsing(static fn (Column $column): Column => $column->translateLabel());
        Entry::configureUsing(static fn (Entry $entry): Entry => $entry->translateLabel());
        Action::configureUsing(static fn (Action $action): Action => $action->translateLabel());
        BaseFilter::configureUsing(static fn (BaseFilter $filter): BaseFilter => $filter->translateLabel());

        $this->app->bind(BaseResourceClassGenerator::class, ResourceClassGenerator::class);
        Relation::enforceMorphMap([
            'bug_report' => BugReport::class,
            'complaint' => Complaint::class,
            'customer' => Customer::class,
            'customer_consent' => CustomerConsent::class,
            'customer_contact' => CustomerContact::class,
            'email_template' => EmailTemplate::class,
            'google_calendar_token' => GoogleCalendarToken::class,
            'interaction' => Interaction::class,
            'invoice' => Invoice::class,
            'lead_score' => LeadScore::class,
            'opportunity' => Opportunity::class,
            'order' => Order::class,
            'product' => Product::class,
            'quote' => Quote::class,
            'support_ticket' => SupportTicket::class,
            'support_ticket_message' => SupportTicketMessage::class,
            'task' => Task::class,
            'user' => User::class,
        ]);
    }
}
