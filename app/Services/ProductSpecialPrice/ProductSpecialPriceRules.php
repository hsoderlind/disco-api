<?php

namespace App\Services\ProductSpecialPrice;

use App\Interfaces\IRules;

class ProductSpecialPriceRules implements IRules
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
        return [
            'special_price' => 'required|integer|numeric|min:0',
            'entry_date' => 'required|date',
            'expiration_date' => 'sometimes|nullable|date',
            'product_id' => 'required|integer|numeric|exists:products,id',
        ];
    }
}
