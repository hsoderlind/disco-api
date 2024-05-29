<?php

namespace App\Services\PaymentMethod;

use App\Validation\Rules;

class PaymentModulesRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access payment methods');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'POST' || $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [];
    }
}
