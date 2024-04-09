<?php

namespace App\Http\Requests;

use App\Services\ProductAttribute\ProductAttributeRules;
use Illuminate\Foundation\Http\FormRequest;

class AttributeProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = new ProductAttributeRules();

        if (! $rules->shouldValidate($this->getMethod())) {
            return [];
        }

        return $rules->rules();
    }
}
