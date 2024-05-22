<?php

namespace App\Http\Requests;

use App\Services\Company\CompanyRules;
use App\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    protected CompanyRules $rules;

    protected function prepareForValidation()
    {
        $this->rules = new CompanyRules($this);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->rules->authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return $this->rules->shouldValidate() ? $this->rules->getRules() : [];
    }
}
