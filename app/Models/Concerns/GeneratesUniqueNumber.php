<?php

declare(strict_types=1);

namespace App\Models\Concerns;

trait GeneratesUniqueNumber
{
    abstract public function uniqueNumberField(): string;

    abstract public static function uniqueNumberPrefix(): string;

    public static function bootGeneratesUniqueNumber(): void
    {
        static::creating(function (self $model): void {
            $field = $model->uniqueNumberField();

            if (! empty($model->{$field})) {
                return;
            }

            $model->{$field} = static::generateNextNumber();
        });
    }

    public static function generateNextNumber(): string
    {
        $prefix = static::uniqueNumberPrefix();
        $field = (new static)->uniqueNumberField();
        $year = now()->year;
        $pattern = $prefix.'-'.$year.'-';

        $lastRecord = static::query()
            ->where($field, 'like', $pattern.'%')
            ->orderByDesc($field)
            ->first();

        $nextNumber = 1;

        if ($lastRecord) {
            $lastNumber = (int) mb_substr((string) $lastRecord->{$field}, -4);
            $nextNumber = $lastNumber + 1;
        }

        return $pattern.mb_str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
