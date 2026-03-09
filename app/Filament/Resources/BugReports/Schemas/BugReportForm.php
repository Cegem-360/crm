<?php

declare(strict_types=1);

namespace App\Filament\Resources\BugReports\Schemas;

use App\Enums\BugReportStatus;
use App\Enums\ComplaintSeverity;
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
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->required()
                    ->placeholder(__('e.g., Button not working on dashboard'))
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->required()
                    ->placeholder(__('Describe the bug in detail...'))
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
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->default(static fn (): ?int => auth()->id()),
                Select::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                TextInput::make('url')
                    ->url()
                    ->placeholder('https://...')
                    ->columnSpanFull(),
                TextInput::make('browser_info')
                    ->placeholder(__('e.g., Chrome 120, macOS'))
                    ->columnSpanFull(),
                FileUpload::make('screenshots')
                    ->multiple()
                    ->image()
                    ->directory('bug-reports')
                    ->visibility('private')
                    ->columnSpanFull(),
            ]);
    }
}
