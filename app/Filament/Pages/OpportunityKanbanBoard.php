<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Enums\OpportunityStage;
use App\Models\Opportunity;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Override;
use UnitEnum;

final class OpportunityKanbanBoard extends Page
{
    /** @var array<string, array<int, array<string, mixed>>> */
    public array $stages = [];

    protected string $view = 'filament.pages.opportunity-kanban-board';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Sales;

    protected static ?int $navigationSort = 15;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Kanban Board');
    }

    #[Override]
    public function getTitle(): string
    {
        return __('Opportunity Kanban Board');
    }

    public function mount(): void
    {
        $this->authorize('viewAny', Opportunity::class);
        $this->loadOpportunities();
    }

    public function moveOpportunity(int $opportunityId, string $newStage): void
    {
        $opportunity = Opportunity::query()->findOrFail($opportunityId);

        $this->authorize('update', $opportunity);

        $stage = OpportunityStage::tryFrom($newStage);

        if (! $stage) {
            return;
        }

        if ($opportunity->stage === $stage) {
            return;
        }

        $opportunity->update(['stage' => $stage]);

        $this->loadOpportunities();

        Notification::make()
            ->title(__(':title moved to :stage', [
                'title' => $opportunity->title,
                'stage' => $stage->getLabel(),
            ]))
            ->success()
            ->send();
    }

    /**
     * @return array<OpportunityStage>
     */
    public function getStageEnums(): array
    {
        return OpportunityStage::cases();
    }

    public function getStageColor(string $stageValue): string
    {
        $stage = OpportunityStage::from($stageValue);

        return match ($stage->getColor()) {
            'gray' => 'bg-gray-500',
            'info' => 'bg-blue-500',
            'warning' => 'bg-amber-500',
            'primary' => 'bg-indigo-500',
            'success' => 'bg-emerald-500',
            'danger' => 'bg-red-500',
            default => 'bg-gray-500',
        };
    }

    public function getStageLabel(string $stageValue): string
    {
        return OpportunityStage::from($stageValue)->getLabel();
    }

    private function loadOpportunities(): void
    {
        $opportunities = Opportunity::query()
            ->with(['customer', 'assignedUser'])
            ->latest('updated_at')
            ->get();

        $this->stages = [];

        foreach (OpportunityStage::cases() as $stage) {
            $this->stages[$stage->value] = $opportunities
                ->where('stage', $stage)
                ->values()
                ->toArray();
        }
    }
}
