<?php

namespace App\Services\Customer;

use App\Services\Account\AccountRules;
use App\Traits\RulesMerger;
use App\Validation\Rules;

class CustomerRules extends Rules
{
    use RulesMerger;

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
            'id' => 'sometimes|required|exists:customers,id',
            'person_name' => 'sometimes|nullable|string|max:255',
            'company_name' => 'sometimes|nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'sometimes|nullable|string|max:255',
            'ssn' => 'sometimes|required_with:person_name',
            'orgno' => 'sometimes|required_with:company_name',
            'vatno' => 'sometimes|nullable|string|max:255',
            'taxable' => 'boolean',
            'currency' => 'string|max:3',
            'note' => 'sometimes|nullable|string',
            // ...$this->merge('account', new AccountRules($this->request), 'sometimes'),
            ...$this->merge('shipping_address', new AccountRules($this->request), 'sometimes'),
            ...$this->merge('billing_address', new AccountRules($this->request), 'sometimes'),
        ];
    }
}
