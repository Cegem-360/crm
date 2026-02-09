<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quotes\Pages;

use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\Quotes\QuoteResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateQuote extends CreateRecord
{
    use ManagesCustomFields;

    protected static string $resource = QuoteResource::class;
}
