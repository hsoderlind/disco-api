<?php

namespace App\Services\Orders;

use App\Validation\Rules;

class OrderTotalsRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access order');
    }

    public function shouldValidate(): bool
    {
        return in_array($this->request->getMethod(), ['POST', 'PUT']);
    }

    public function getRules(): array
    {
        return [
            'id' => 'sometimes|required|integer|exists:order_totals,id',
            'label' => 'required|string|max:255',
            'value' => 'required|integer',
            'sort_order' => 'required|integer',
        ];
    }
}
