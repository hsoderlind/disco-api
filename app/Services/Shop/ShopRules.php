<?php

namespace App\Services\Shop;

use App\Validation\Rules;

class ShopRules extends Rules
{
    public function authorize(): bool
    {
        return true;
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() == 'POST' || $this->request->getMethod() == 'PUT';
    }

    public function getRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'orgnumber' => 'required|string|unique:shops|max:12',
            'address_street1' => 'required|string|max:255',
            'address_street2' => 'nullable|string|max:255',
            'address_zip' => 'required|string|max:255',
            'address_city' => 'required|string|max:255',
            'official_website' => 'sometimes|nullable|url|max:255',
            'support_website' => 'sometimes|nullable|url|max:255',
            'terms_of_agreement_url' => 'sometimes|nullable|url|max:255',
            'privacy_police_url' => 'sometimes|nullable|url|max:255',
            'support_phone' => 'sometimes|string|max:255',
            'support_email' => 'sometimes|email|max:255',
        ];
    }
}
