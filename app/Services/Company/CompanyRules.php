<?php

namespace App\Services\Company;

use App\Services\Account\AccountRules;
use App\Traits\RulesMerger;
use App\Validation\Rules;

class CompanyRules extends Rules
{
    use RulesMerger;

    public function authorize(): bool
    {
        return $this->request->user()->can('access company profile');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [
            'id' => 'sometimes|required|exists:companies,id',
            'name' => 'required|string|max:255',
            'orgnumber' => 'required|entity:organization',
            'official_website' => 'sometimes|nullable|url|max:255',
            'support_website' => 'sometimes|nullable|url|max:255',
            'terms_of_agreement_url' => 'sometimes|nullable|url|max:255',
            'privacy_police_url' => 'sometimes|nullable|url|max:255',
            'support_phone' => 'sometimes|nullable|string|max:255',
            'support_email' => 'sometimes|nullable|email|max:255',
            ...$this->merge('support_address', new AccountRules($this->request), 'sometimes', checkArrayKeys: false, omitFields: ['name', 'phone', 'country']),
        ];
    }
}
