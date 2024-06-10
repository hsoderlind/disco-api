<?php

namespace App\Services\ShippingMethod;

use App\Validation\Rules;

class ShippingModulesRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access shipping method');
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
