<?php

namespace App\Services\Tax;

use App\Interfaces\IRules;

class TaxRules implements IRules
{
    public function authorize(mixed $user): bool
    {
        return $user->can('access tax');
    }

    public function shouldValidate(string $requestMethod): bool
    {
        return $requestMethod === 'POST' || $requestMethod === 'PUT';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'value' => 'required|integer|min:0',
            'priority' => 'required|integer|min:0',
        ];
    }
}
