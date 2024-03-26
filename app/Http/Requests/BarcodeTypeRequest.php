<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarcodeTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('access product');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if ($this->getMethod() === 'GET' || $this->getMethod() === 'DELETE') {
            return [];
        }

        return [
            'label' => 'required|string|max:255',
            'format' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'active' => 'required|boolean',
        ];
    }
}
