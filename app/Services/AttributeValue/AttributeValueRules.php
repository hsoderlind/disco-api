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
        ];
    }
}
