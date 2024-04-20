<?php

namespace App\Services\ProductStock;

use App\Validation\Rules;

class ProductStockRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access stock');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() == 'POST' || $this->request->getMethod() == 'PUT';
    }

    public function getRules(): array
    {
        return [
            'sku' => 'string|nullable|max:255',
            'initial_quantity' => 'sometimes|required|integer|numeric',
            'min_order_quantity' => 'required|integer|numeric',
            'out_of_stock_message' => 'string|nullable|max:255',
            'allow_order_out_of_stock' => 'boolean',
            'send_email_out_of_stock' => 'boolean',
            'in_stock_message' => 'string|nullable|max:255',
            'available_at' => 'date_format:Y-m-d|nullable',
        ];
    }
}
