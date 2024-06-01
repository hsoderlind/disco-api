<?php

namespace App\Services\Orders;

use App\Validation\Rules;

class OrderTotalRepositoryRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access order total modules');
    }

    public function shouldValidate(): bool
    {
        return $this->request->isMethod('post') || $this->request->isMethod('put');
    }

    public function getRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'active' => 'required|boolean',
            'admin_component' => 'nullable|string|max:255',
            'configuration' => 'nullable|array',
        ];
    }
}
