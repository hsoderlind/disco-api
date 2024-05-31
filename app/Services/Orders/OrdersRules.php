<?php

namespace App\Services\Orders;

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
            'id' => 'sometimes|required|integer|exists:orders,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'status' => 'required|string|max:255',
            ...$this->merge('items', new OrderItemsRules($this->request), isArray: true),
            ...$this->merge('payment_method', new OrderPaymentRules($this->request)),
            ...$this->merge('totals', new OrderTotalsRules($this->request), isArray: true),
        ];
    }
}
