<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

final class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Customer::class);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'unique_identifier' => ['required', 'string', 'unique:customers'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:individual,company'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'phone' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'team_id' => ['sometimes', 'exists:teams,id'],
        ];
    }
}
