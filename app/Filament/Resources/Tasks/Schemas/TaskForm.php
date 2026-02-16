<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tasks\Schemas;

use App\Enums\CustomFieldModel;
use App\Filament\Concerns\HasCustomFieldsSchema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class TaskForm
{
    use HasCustomFieldsSchema;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label(__('Customer'))
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('assigned_by')
                    ->relationship('assigner', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->required()
                    ->placeholder(__('e.g., Follow up with client')),
                Textarea::make('description')
                    ->placeholder(__('Task details and instructions...'))
                    ->columnSpanFull(),
                Select::make('priority')
                    ->required()
                    ->options([
                        'low' => __('Low'),
                        'medium' => __('Medium'),
                        'high' => __('High'),
                        'urgent' => __('Urgent'),
                    ])
                    ->default('medium'),
                Select::make('status')
                    ->required()
                    ->options([
                        'pending' => __('Pending'),
                        'in_progress' => __('In Progress'),
                        'completed' => __('Completed'),
                        'cancelled' => __('Cancelled'),
                    ])
                    ->default('pending'),
                DatePicker::make('due_date'),
                DateTimePicker::make('completed_at'),
                ...self::getCustomFieldsFormSection(CustomFieldModel::Task),
            ]);
    }
}
