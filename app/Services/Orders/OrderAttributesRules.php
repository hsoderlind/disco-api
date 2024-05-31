<?php

namespace App\Services\Orders;

use App\Validation\Rules;

class OrderAttributesRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access order');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() == 'POST' || $this->request->getMethod() == 'PUT';
    }

    public function getRules(): array
    {
        return [
            'attribute_product_id' => 'required|integer|exists:attribute_products,id',
            'attribute_type_id' => 'required|integer|exists:attribute_types,id',
            'attribute_value_id' => 'required|integer|exists:attribute_value,id',
            'order_item_id' => 'sometimes|required|exists:order_items,id',
            'attribute_type_name' => 'required|string|max:255',
            'attribute_value_name' => 'required|string|max:255',
            'price' => 'required|integer',
            'total' => 'required|integer',
            'vat' => 'required|integer',
            'tax_value' => 'required|integer',
            'quantity' => 'required|integer',
        ];
    }
}
