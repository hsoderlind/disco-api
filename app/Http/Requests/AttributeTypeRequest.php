<?php

namespace App\Http\Requests;

use App\Services\AttributeType\AttributeTypeRules;
use Illuminate\Foundation\Http\FormRequest;

class AttributeTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (new AttributeTypeRules())->authorize($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = new AttributeTypeRules();

        if (! $rules->shouldValidate($this->getMethod())) {
            return [];
        }

        return $rules->rules();
    }
}
