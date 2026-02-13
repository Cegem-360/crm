<?php

declare(strict_types=1);

use App\Enums\OpportunityStage;
use App\Enums\Permission;
use App\Filament\Resources\BugReports\Pages\ListBugReports;
use App\Filament\Resources\Campaigns\Pages\ListCampaigns;
use App\Filament\Resources\ChatMessages\Pages\ListChatMessages;
use App\Filament\Resources\ChatSessions\Pages\ListChatSessions;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Filament\Resources\Complaints\Pages\ListComplaints;
use App\Filament\Resources\Contacts\Pages\ListContacts;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Discounts\Pages\ListDiscounts;
use App\Filament\Resources\Interactions\Pages\ListInteractions;
use App\Filament\Resources\Invoices\Pages\ListInvoices;
use App\Filament\Resources\LeadOpportunities\Pages\ListLeadOpportunities;
use App\Filament\Resources\LostQuotationOpportunities\Pages\ManageLostQuotationOpportunities;
use App\Filament\Resources\NegotiationOpportunities\Pages\ManageNegotiationOpportunities;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\ProductCategories\Pages\ListProductCategories;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\ProposalOpportunities\Pages\ManageProposalOpportunities;
use App\Filament\Resources\QualifiedOpportunities\Pages\ManageQualifiedOpportunities;
use App\Filament\Resources\QuotationSendedOpportunities\Pages\ManageQuotationSendedOpportunities;
use App\Filament\Resources\Quotes\Pages\ListQuotes;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\BugReport;
use App\Models\Campaign;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Company;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\Discount;
use App\Models\Interaction;
use App\Models\Invoice;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Quote;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Spatie\Permission\Models\Permission as PermissionModel;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();

    foreach (Permission::values() as $permissionName) {
        PermissionModel::query()->firstOrCreate(['name' => $permissionName]);
    }

    $this->user->givePermissionTo(Permission::values());

    $this->actingAs($this->user);
    $this->team = setUpFilamentTenant($this->user);
});

dataset('tenant-scoped-resources', [
    'customers' => [
        ListCustomers::class,
        fn (Team $team): Customer => Customer::factory()->for($team)->create(),
    ],
    'companies' => [
        ListCompanies::class,
        fn (Team $team): Company => Company::factory()->for($team)->create(),
    ],
    'products' => [
        ListProducts::class,
        fn (Team $team): Product => Product::factory()->for($team)->create(),
    ],
    'product categories' => [
        ListProductCategories::class,
        fn (Team $team): ProductCategory => ProductCategory::factory()->for($team)->create(),
    ],
    'orders' => [
        ListOrders::class,
        fn (Team $team): Order => Order::factory()->for($team)->create(),
    ],
    'quotes' => [
        ListQuotes::class,
        fn (Team $team): Quote => Quote::factory()->for($team)->create(),
    ],
    'invoices' => [
        ListInvoices::class,
        fn (Team $team): Invoice => Invoice::factory()->for($team)->create(),
    ],
    'discounts' => [
        ListDiscounts::class,
        fn (Team $team): Discount => Discount::factory()->for($team)->create(),
    ],
    'tasks' => [
        ListTasks::class,
        fn (Team $team): Task => Task::factory()->for($team)->create(),
    ],
    'complaints' => [
        ListComplaints::class,
        fn (Team $team): Complaint => Complaint::factory()->for($team)->create(),
    ],
    'interactions' => [
        ListInteractions::class,
        fn (Team $team): Interaction => Interaction::factory()->for($team)->create(),
    ],
    'campaigns' => [
        ListCampaigns::class,
        fn (Team $team): Campaign => Campaign::factory()->for($team)->create(),
    ],
    'contacts' => [
        ListContacts::class,
        fn (Team $team): CustomerContact => CustomerContact::factory()->for($team)->create(),
    ],
    'chat sessions' => [
        ListChatSessions::class,
        fn (Team $team): ChatSession => ChatSession::factory()->for($team)->create(),
    ],
    'chat messages' => [
        ListChatMessages::class,
        fn (Team $team): ChatMessage => ChatMessage::factory()->for($team)->create(),
    ],
    'bug reports' => [
        ListBugReports::class,
        fn (Team $team): BugReport => BugReport::factory()->for($team)->create(),
    ],
    'lead opportunities' => [
        ListLeadOpportunities::class,
        fn (Team $team): Opportunity => Opportunity::factory()->for($team)->create(['stage' => OpportunityStage::Lead]),
    ],
    'qualified opportunities' => [
        ManageQualifiedOpportunities::class,
        fn (Team $team): Opportunity => Opportunity::factory()->for($team)->create(['stage' => OpportunityStage::Qualified]),
    ],
    'proposal opportunities' => [
        ManageProposalOpportunities::class,
        fn (Team $team): Opportunity => Opportunity::factory()->for($team)->create(['stage' => OpportunityStage::Proposal]),
    ],
    'negotiation opportunities' => [
        ManageNegotiationOpportunities::class,
        fn (Team $team): Opportunity => Opportunity::factory()->for($team)->create(['stage' => OpportunityStage::Negotiation]),
    ],
    'sent quotation opportunities' => [
        ManageQuotationSendedOpportunities::class,
        fn (Team $team): Opportunity => Opportunity::factory()->for($team)->create(['stage' => OpportunityStage::SentQuotation]),
    ],
    'lost quotation opportunities' => [
        ManageLostQuotationOpportunities::class,
        fn (Team $team): Opportunity => Opportunity::factory()->for($team)->create(['stage' => OpportunityStage::LostQuotation]),
    ],
]);

it('cannot see records from other teams in table', function (string $listPageClass, Closure $createRecord): void {
    $otherTeam = Team::factory()->create();
    $otherRecord = $createRecord($otherTeam);
    $ownRecord = $createRecord($this->team);

    livewire($listPageClass)
        ->assertCanSeeTableRecords([$ownRecord])
        ->assertCanNotSeeTableRecords([$otherRecord]);
})->with('tenant-scoped-resources');

it('cannot see users from other teams in table', function (): void {
    $otherTeam = Team::factory()->create();
    $otherUser = User::factory()->create();
    $otherUser->teams()->attach($otherTeam);

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords([$this->user])
        ->assertCanNotSeeTableRecords([$otherUser]);
});
