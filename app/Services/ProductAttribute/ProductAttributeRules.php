<?php

namespace App\Services\ProductAttribute;

use App\Interfaces\IRules;
use App\Services\ProductAttributeStock\ProductAttributeStockRules;

class ProductsAttributeRules implements IRules
{
    public function authorize(mixed $user): bool
    {
        return $user->can('access product');
    }

    public function shouldValidate(string $requestMethod): bool
    {
        return $requestMethod === 'POST' || $requestMethod === 'PUT';
    }

    public function rules(): array
    {
        $stockRules = (new ProductAttributeStockRules())->rules();

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
