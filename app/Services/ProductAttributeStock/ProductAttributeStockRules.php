<?php

namespace App\Services\ProductAttributeStock;

use App\Interfaces\IRules;

class ProductAttributeStockRules implements IRules
{
    public function authorize(mixed $user): bool
    {
        return $user->can('access product');
    }

    public function shouldValidate(string $requestMethod): bool
    {
        return $requestMethod === 'PUT' || $requestMethod === 'POST';
    }

    public function rules(): array
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
