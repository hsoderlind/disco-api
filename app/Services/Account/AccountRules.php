<?php

namespace App\Services\Account;

use App\Validation\Rules;

class AccountRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access customer');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'PUT' || $this->request->getMethod() === 'POST';
    }

    public function getRules(): array
    {
        return [
            'id' => 'sometimes|required|exists:accounts,id',
            'name' => 'required|string|max:255',
            'address1' => 'sometimes|nullable|string|max:255',
            'address2' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'state' => 'sometimes|nullable|string|max:255',
            'zip' => 'sometimes|nullable|string|max:6',
            'country' => 'sometimes|nullable|string|max:255',
            'phone' => 'sometimes|nullable|string|max:255',
        ];
    }
}
