<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use App\Services\CustomFieldService;

trait ManagesCustomFields
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($this->record) && method_exists($this->record, 'customFieldValues')) {
            $customFieldData = app(CustomFieldService::class)->prepareFormData($this->record);
            $data = array_merge($data, $customFieldData);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->saveCustomFieldsFromForm();

        if (method_exists(parent::class, 'afterSave')) {
            parent::afterSave();
        }
    }

    protected function afterCreate(): void
    {
        $this->saveCustomFieldsFromForm();

        if (method_exists(parent::class, 'afterCreate')) {
            parent::afterCreate();
        }
    }

    protected function saveCustomFieldsFromForm(): void
    {
        if (isset($this->record) && method_exists($this->record, 'saveCustomFieldValues')) {
            $data = $this->form->getState();
            app(CustomFieldService::class)->saveFormData($this->record, $data);
        }
    }
}
