<?php

namespace App\Services\Shop;

use App\Validation\Rules;

class ShopUserRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access shop staff');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() == 'POST' || $this->request->getMethod() == 'PUT';
    }

    public function getRules(): array
    {
        return [
            'id' => 'sometimes|required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'roles.*' => 'required|numeric|integer',
        ];
    }
}
