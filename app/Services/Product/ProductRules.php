<?php

namespace App\Services\Product;

use App\Services\Barcode\BarcodeRules;
use App\Services\ProductAttribute\ProductAttributeRules;
use App\Services\ProductFile\ProductFileRules;
use App\Services\ProductImage\ProductImageRules;
use App\Services\ProductSpecialPrice\ProductSpecialPriceRules;
use App\Services\ProductStock\ProductStockRules;
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
            'item_number' => 'nullable|string|max:255',
            'price' => 'required|numeric|integer',
            'cost_price' => 'sometimes|required|numeric|integer',
            'reference' => 'nullable|string|max:255',
            'supplier_reference' => 'nullable|string|max:255',
            'condition' => [Rule::enum(ProductCondition::class)],
            'name' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'categories.*' => 'integer',
            ...$this->merge('barcodes', new BarcodeRules($this->request), 'sometimes', true),
            ...$this->merge('product_attributes', new ProductAttributeRules($this->request), 'sometimes', true),
            ...$this->merge('special_prices', new ProductSpecialPriceRules($this->request), 'sometimes', true),
            ...$this->merge('stock', new ProductStockRules($this->request), 'sometimes'),
            ...$this->merge('images', new ProductImageRules($this->request), 'sometimes', true),
            ...$this->merge('files', new ProductFileRules($this->request), 'sometimes', true),
        ];

        return $rules;
    }
}
