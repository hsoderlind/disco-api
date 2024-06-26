<?php

namespace App\Http\Requests;

use App\Services\AttributeValue\AttributeValueRules;
use App\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class AttributeValueRequest extends FormRequest
{
    protected Rules $rules;

    protected function prepareForValidation()
    {
        $this->rules = new AttributeValueRules($this);
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
        if (! $this->rules->shouldValidate()) {
            return [];
        }

        return $this->rules->getRules();
    }
}
