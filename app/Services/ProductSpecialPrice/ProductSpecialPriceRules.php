<?php

namespace App\Services\ProductSpecialPrice;

use App\Validation\Rules;

class ProductSpecialPriceRules extends Rules
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
        return [
            'id' => 'sometimes|required|exists:product_special_prices,id',
            'special_price' => 'required|integer|numeric|min:0',
            'entry_date' => 'required|date',
            'expiration_date' => 'sometimes|nullable|date',
            'product_id' => 'sometimes|required|integer|numeric|exists:products,id',
        ];
    }
}
