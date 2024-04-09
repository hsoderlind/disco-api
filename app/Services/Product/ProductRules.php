<?php

namespace App\Services\Product;

use App\Interfaces\IRules;
use App\Services\ProductAttribute\ProductAttributeRules;
use App\Services\ProductSpecialPrice\ProductSpecialPriceRules;
use App\Traits\RulesMerger;
use Illuminate\Validation\Rule;

class ProductRules implements IRules
{
    use RulesMerger;

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
            ...$this->merge('product_attributes', new ProductAttributeRules(), 'sometimes', true),
            ...$this->merge('special_prices', new ProductSpecialPriceRules(), 'sometimes', true),
        ];

        return $rules;
    }
}
