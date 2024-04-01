<?php

namespace App\Services\Product;

use App\Interfaces\IRules;
use App\Services\ProductAttribute\ProductsAttributeRules;
use Illuminate\Validation\Rule;

class ProductRules implements IRules
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
        $productAttributeRules = (new ProductsAttributeRules())->rules();
        $joinedProductAttributeFields = implode(',', array_keys($productAttributeRules));

        $rules = [
            'tax_id' => 'integer',
            'supplier_id' => 'integer',
            'manufacturer_id' => 'integer',
            'price' => 'required|numeric|integer',
            'reference' => 'nullable|string|max:255',
            'supplier_reference' => 'nullable|string|max:255',
            'available_for_order' => 'boolean',
            'available_at' => 'sometimes|nullable|date',
            'condition' => [Rule::enum(ProductCondition::class)],
            'name' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'barcodes.*' => 'sometimes|integer',
            'categories.*' => 'integer',
            'product_attributes' => 'sometimes|array:'.$joinedProductAttributeFields,
        ];

        foreach ($productAttributeRules as $field => $rule) {
            $rules['product_attributes.*.'.$field] = $rule;
        }

        return $rules;
    }
}
