<?php

namespace App\Services\ProductAttributeStock;

use App\Validation\Rules;

class ProductAttributeStockRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access product');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'PUT' || $this->request->getMethod() === 'POST';
    }

    public function getRules(): array
    {
        return [
            'sku' => 'sometimes|string|nullable|max:255',
            'stock_unit' => 'sometimes|string|nullable|max:255',
            'out_of_stock_message' => 'sometimes|string|nullable|max:255',
            'available_at' => 'sometimes|date_format:Y-m-d',
            'allow_order_out_of_stock' => 'sometimes|boolean',
            'initial_quantity' => 'required|integer|numeric',
            'reserved_quantity' => 'sometimes|integer|numeric',
            'sold_quantity' => 'sometimes|integer|numeric',
        ];
    }
}
