<?php

namespace App\Services\ProductAttribute;

use App\Services\ProductAttributeStock\ProductAttributeStockRules;
use App\Traits\RulesMerger;
use App\Validation\Rules;

class ProductAttributeRules extends Rules
{
    use RulesMerger;

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
        $rules = [
            'id' => 'sometimes|required|exists:attribute_products,id',
            'attribute_type_id' => 'required|integer|numeric|exists:\App\Models\AttributeType,id',
            'attribute_value_id' => 'required|integer|numeric|exists:\App\Models\AttributeValue,id',
            'sort_order' => 'required|integer|numeric|min:0',
            'active' => 'required|boolean',
            ...$this->merge('stock', new ProductAttributeStockRules($this->request), 'sometimes'),
        ];

        return $rules;
    }
}
