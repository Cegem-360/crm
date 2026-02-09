<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\Tasks\TaskResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateTask extends CreateRecord
{
    use ManagesCustomFields;

    protected static string $resource = TaskResource::class;
}
