<?php

namespace App\Services\OrderStatus;

use App\Rules\UniqueInShop;
use App\Validation\Rules;

class OrderStatusRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access order status');
    }

    public function shouldValidate(): bool
    {
        return $this->request->isMethod('post') || $this->request->isMethod('put');
    }

    public function getRules(): array
    {
        return [
            'id' => 'sometimes|required|exists:order_statuses,id',
            'name' => ['required', 'string', 'max:255', new UniqueInShop('order_statuses', except: 'id')],
            'sort_order' => 'required|numeric|integer|min:0',
            'is_default' => 'required|boolean',
        ];
    }
}
