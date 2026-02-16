<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\LeadScore;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

final class TopLeadsWidget extends BaseWidget
{
    protected static ?string $heading = null;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): string
    {
        return __('Top Scoring Leads');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LeadScore::query()
                    ->with(['customer', 'assignedUser'])
                    ->orderByDesc('score')
                    ->limit(10),
            )
            ->columns([
                TextColumn::make('customer.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('score')
                    ->label(__('Total Score'))
                    ->sortable()
                    ->badge()
                    ->color(static fn (int $state): string => match (true) {
                        $state >= 70 => 'success',
                        $state >= 40 => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('interaction_score')
                    ->label(__('Interactions'))
                    ->sortable(),
                TextColumn::make('recency_score')
                    ->label(__('Recency'))
                    ->sortable(),
                TextColumn::make('opportunity_score')
                    ->label(__('Opportunities'))
                    ->sortable(),
                TextColumn::make('engagement_score')
                    ->label(__('Engagement'))
                    ->sortable(),
                TextColumn::make('assignedUser.name')
                    ->label(__('Assigned To'))
                    ->placeholder(__('Unassigned'))
                    ->sortable(),
                TextColumn::make('last_calculated_at')
                    ->label(__('Last Calculated'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
