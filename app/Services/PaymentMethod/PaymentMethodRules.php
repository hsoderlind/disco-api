<?php

namespace App\Services\PaymentMethod;

use App\Validation\Rules;

class PaymentMethodRules extends Rules
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
        return [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'sort_order' => 'sometimes|nullable|numeric|integer',
            'active' => 'boolean',
            'fee' => 'sometimes|nullable|numeric|integer',
            'control_class' => 'sometimes|required|string|max:255',
            'component' => 'required|string|max:255',
            'admin_component' => 'required|string|max:255',
            'configuration' => 'sometimes|nullable|array',
        ];
    }
}
