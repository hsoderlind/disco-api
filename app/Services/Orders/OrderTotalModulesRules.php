<?php

namespace App\Services\Orders;

use App\Validation\Rules;

class OrderTotalModulesRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access order total modules');
    }

    public function shouldValidate(): bool
    {
        return $this->request->isMethod('POST') || $this->request->isMethod('PUT');
    }

    public function getRules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
