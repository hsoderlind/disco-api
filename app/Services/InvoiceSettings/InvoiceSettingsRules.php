<?php

namespace App\Services\InvoiceSettings;

use App\Validation\Rules;

class InvoiceSettingsRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access invoice settings');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [
            'show_orgnumber' => 'boolean',
            'show_company_name' => 'boolean',
            'show_shop_name' => 'boolean',
            'show_company_official_website' => 'boolean',
            'show_shop_official_website' => 'boolean',
            'show_company_support_url' => 'boolean',
            'show_shop_support_url' => 'boolean',
            'show_company_terms_of_agreement_url' => 'boolean',
            'show_shop_terms_of_agreement_url' => 'boolean',
            'show_company_privacy_police_url' => 'boolean',
            'show_shop_privacy_police_url' => 'boolean',
            'show_company_support_phone' => 'boolean',
            'show_shop_support_phone' => 'boolean',
            'show_company_support_email' => 'boolean',
            'show_shop_support_email' => 'boolean',
            'show_support_address' => 'boolean',
            'show_shop_address' => 'boolean',
        ];
    }
}
