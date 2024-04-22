<?php

namespace App\Services\AttributeValue;

use App\Validation\Rules;

class AttributeValueRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access product');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'POST' || $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [
            'label' => 'required|string|max:255',
            'sort_order' => 'required|integer|numeric|min:0',
            'attribute_type_id' => 'required|integer|numeric|exists:attribute_types,id',
        ];
    }
}
