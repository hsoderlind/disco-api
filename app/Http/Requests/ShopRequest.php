<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'orgnumber' => 'required|string|unique:shops|max:12',
            'address_street1' => 'string|max:255',
            'address_street2' => 'string|max:255',
            'address_zip' => 'string|max:255',
            'address_city' => 'string|max:255',
        ];
    }
}
