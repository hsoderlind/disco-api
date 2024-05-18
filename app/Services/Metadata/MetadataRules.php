<?php

namespace App\Services\Metadata;

use App\Validation\Rules;

class MetadataRules extends Rules
{
    public function authorize(): bool
    {
        return true;
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'POST' || $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [
            'id' => 'sometimes|required|exists:metadata,id',
            'key' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ];
    }
}
