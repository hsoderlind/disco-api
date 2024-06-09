<?php

namespace App\Services\Orders;

use App\Rules\ExistsInShop;
use App\Traits\RulesMerger;
use App\Validation\Rules;

class OrdersRules extends Rules
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
            'id' => ['sometimes', 'required', new ExistsInShop('orders')],
            'customer_id' => 'required|exists:customers,id',
            'payment_name' => ['required', 'string', new ExistsInShop('payment_methods', 'name')],
            ...$this->merge('items', new OrderItemsRules($this->request), 'required', true, checkArrayKeys: false),
            'totals' => 'required|array',
            'totals.*.name' => 'required|string|max:255',
            'totals.*.entries.*.label' => 'required|string|max:255',
            'totals.*.entries.*.value' => 'required|numeric|integer',
            'totals.*.entries.*.sort_order' => 'required|numeric|integer',
            'totals.*.sort_order' => 'required|numeric|integer',
            'note' => 'nullable|string',
        ];
    }
}
