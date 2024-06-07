<?php

namespace App\Services\Orders;

use App\Rules\ExistsInShop;
use App\Traits\RulesMerger;
use App\Validation\Rules;

class OrderItemsRules extends Rules
{
    use RulesMerger;

    public function authorize(): bool
    {
        return $this->request->user()->can('access order');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() == 'POST' || $this->request->getMethod() == 'PUT';
    }

    public function getRules(): array
    {
        return [
            'product_id' => ['required', new ExistsInShop('products', 'id')],
            'tax_id' => ['required', new ExistsInShop('taxes', 'id')],
            'product_name' => 'required|string|max:255',
            'price' => 'required|integer',
            'total' => 'required|integer',
            'vat' => 'required|integer',
            'tax_value' => 'required|integer',
            'quantity' => 'required|integer',
            ...$this->merge('itemAttributes', new OrderAttributesRules($this->request), 'sometimes', true),
        ];
    }
}
