<?php

declare(strict_types=1);

namespace App\Filament\Resources\ChatSessions\Pages;

use Override;
use App\Filament\Resources\ChatSessions\ChatSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditChatSession extends EditRecord
{
    protected static string $resource = ChatSessionResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
