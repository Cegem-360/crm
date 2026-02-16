<?php

declare(strict_types=1);

namespace App\Filament\Resources\BugReports\Schemas;

use App\Enums\BugReportStatus;
use App\Enums\ComplaintSeverity;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class BugReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label(__('Customer'))
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('user_id')
                    ->label(__('Reported By'))
                    ->relationship('user', 'name'),
                TextInput::make('title')
                    ->placeholder(__('Brief summary of the bug'))
                    ->required(),
                Textarea::make('description')
                    ->placeholder(__('Steps to reproduce, expected vs actual behavior...'))
                    ->required()
                    ->columnSpanFull(),
                Select::make('severity')
                    ->options(ComplaintSeverity::class)
                    ->enum(ComplaintSeverity::class)
                    ->required()
                    ->default(ComplaintSeverity::Medium),
                Select::make('status')
                    ->options(BugReportStatus::class)
                    ->enum(BugReportStatus::class)
                    ->required()
                    ->default(BugReportStatus::Open),
                TextInput::make('source')
                    ->placeholder(__('e.g., CRM, Website, Mobile App')),
                TextInput::make('url')
                    ->label(__('URL'))
                    ->placeholder(__('Page URL where the bug occurred'))
                    ->url(),
                TextInput::make('browser_info')
                    ->label(__('Browser Info'))
                    ->placeholder(__('Auto-captured from submission'))
                    ->disabled()
                    ->dehydrated(),
                Select::make('assigned_to')
                    ->label(__('Assigned To'))
                    ->relationship('assignedUser', 'name'),
                DateTimePicker::make('resolved_at')
                    ->label(__('Resolved At')),
                FileUpload::make('screenshots')
                    ->helperText(__('Attach screenshots to help illustrate the bug'))
                    ->directory('bug-reports')
                    ->image()
                    ->multiple()
                    ->panelLayout('grid')
                    ->columnSpanFull(),
            ]);
    }
}
