<?php

namespace App\Services\ProductAttribute;

use App\Services\ProductAttributeStock\ProductAttributeStockRules;
use App\Validation\Rules;

class ProductAttributeRules extends Rules
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
        $stockRules = (new ProductAttributeStockRules($this->request))->getRules();

        $joinedStockRuleFields = implode(',', array_keys($stockRules));

        $rules = [
            'attribute_type_id' => 'required|integer|numeric|exists:\App\Models\AttributeType,id',
            'attribute_value_id' => 'required|integer|numeric|exists:\App\Models\AttributeValue,id',
            'sort_order' => 'required|integer|numeric|min:0',
            'active' => 'required|boolean',
            'stock' => 'sometimes|array:'.$joinedStockRuleFields,
        ];

        foreach ($stockRules as $field => $rule) {
            $rules['stock.'.$field] = $rule;
        }

        return $rules;
    }
}
