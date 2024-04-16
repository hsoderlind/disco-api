<?php

namespace App\Services\AttributeType;

use App\Validation\Rules;

class AttributeTypeRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access product');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'POST' ||
            $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [
            'label' => 'required|string|max:255',
            'active' => 'required|boolean',
        ];
    }
}
