<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateProduct extends CreateRecord
{
    use ManagesCustomFields;

    protected static string $resource = ProductResource::class;
}
