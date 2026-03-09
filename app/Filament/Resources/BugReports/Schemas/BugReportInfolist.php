<?php

declare(strict_types=1);

namespace App\Filament\Resources\BugReports\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class BugReportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Bug Report Details'))
                    ->columns(2)
                    ->components([
                        TextEntry::make('title')
                            ->columnSpanFull(),
                        TextEntry::make('description')
                            ->columnSpanFull(),
                        TextEntry::make('severity')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('user.name'),
                        TextEntry::make('assignedUser.name'),
                        TextEntry::make('url')
                            ->url()
                            ->columnSpanFull(),
                        TextEntry::make('browser_info')
                            ->columnSpanFull(),
                        ImageEntry::make('screenshots')
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('resolved_at')
                            ->dateTime(),
                    ]),
            ]);
    }
}
