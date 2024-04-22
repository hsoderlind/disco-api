<?php

namespace App\Services\Tax;

use App\Validation\Rules;

class TaxRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access tax');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'POST' || $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'value' => 'required|integer|min:0',
            'priority' => 'required|integer|min:0',
        ];
    }
}
