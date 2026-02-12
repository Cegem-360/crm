<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Override;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class CustomerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
