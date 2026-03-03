<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\CampaignResponse;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Number;
use Override;

final class CampaignResponseImporter extends Importer
{
    protected static ?string $model = CampaignResponse::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('campaign')
                ->label(__('Campaign'))
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('customer')
                ->label(__('Customer'))
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('response_type')
                ->label(__('Response Type'))
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('notes')
                ->label(__('Notes')),
            ImportColumn::make('responded_at')
                ->label(__('Responded At'))
                ->rules(['datetime']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __(':count campaign response imported successfully.', ['count' => Number::format($import->successful_rows)]);

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to import.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }

    #[Override]
    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label(__('Update existing records')),
        ];
    }

    #[Override]
    public function resolveRecord(): CampaignResponse
    {
        if ($this->options['updateExisting'] ?? false) {
            return CampaignResponse::query()->firstOrNew([
                'id' => $this->data['id'],
            ]);
        }

        return new CampaignResponse();
    }
}
