<?php

namespace App\Services\AttributeValue;

use App\Interfaces\IRules;

class AttributeValueRules implements IRules
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
            'label' => 'required|string|max:255',
            'sort_order' => 'required|integer|numeric|min:0',
            'attribute_type_id' => 'required|integer|numeric|exists:attribute_types,id',
        ];
    }
}
