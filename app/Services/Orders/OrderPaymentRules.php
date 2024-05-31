<?php

namespace App\Services\Orders;

use App\Validation\Rules;

class OrderPaymentRules extends Rules
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
            'id' => 'sometimes|required|integer|exists:order_payments,id',
            'payment_method_name' => 'required|string|max:255',
            'transaction_id' => 'sometimes|nullable|string|max:255',
        ];
    }
}
