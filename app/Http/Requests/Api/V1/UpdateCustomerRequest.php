<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('customer'));
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        /** @var Customer $customer */
        $customer = $this->route('customer');

        return [
            'unique_identifier' => ['sometimes', 'string', 'unique:customers,unique_identifier,'.$customer->id],
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'in:individual,company'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'phone' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
