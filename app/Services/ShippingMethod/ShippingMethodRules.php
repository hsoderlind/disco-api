<?php

namespace App\Services\ShippingMethod;

use App\Rules\ExistsInShop;
use App\Validation\Rules;

class ShippingMethodRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access shipping method');
    }

    public function shouldValidate(): bool
    {
        return $this->request->isMethod('POST') || $this->request->isMethod('PUT');
    }

    public function getRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', new ExistsInShop('shipping_methods')],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'configuration' => 'nullable|array',
            'fee' => 'numeric|integer',
        ];
    }
}
