<?php

namespace App\Http\Requests;

use App\Services\Tax\TaxRules;
use Illuminate\Foundation\Http\FormRequest;

class TaxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (new TaxRules)->authorize($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = new TaxRules;

        if (! $rules->shouldValidate($this->getMethod())) {
            return [];
        }

        return $rules->rules();
    }
}
