<?php

namespace App\Services\CreditBalance;

use App\Validation\Rules;
use Illuminate\Validation\Rule;

class CreditbalanceRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access customer');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'PUT' || $this->request->getMethod() === 'POST';
    }

    public function getRules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'adjusted_balance' => 'required|numeric',
            'adjustment_type' => Rule::in(AdjustmentType::values()),
            'note' => 'nullable|string',
            'id' => 'sometimes|required|exists:credit_balances,id',
        ];
    }
}
