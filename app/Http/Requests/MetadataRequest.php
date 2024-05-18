<?php

namespace App\Http\Requests;

use App\Services\Metadata\MetadataRules;
use App\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class MetadataRequest extends FormRequest
{
    protected MetadataRules $rules;

    protected function prepareForValidation()
    {
        $this->rules = new MetadataRules($this);
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
