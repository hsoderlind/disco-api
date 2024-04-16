<?php

namespace App\Services\Product;

use App\Services\ProductAttribute\ProductAttributeRules;
use App\Services\ProductSpecialPrice\ProductSpecialPriceRules;
use App\Traits\RulesMerger;
use App\Validation\Rules;
use Illuminate\Validation\Rule;

class ProductRules extends Rules
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
            ...$this->merge('product_attributes', new ProductAttributeRules($this->request), 'sometimes', true),
            ...$this->merge('special_prices', new ProductSpecialPriceRules($this->request), 'sometimes', true),
        ];

        return $rules;
    }
}
