<?php

namespace App\Http\Requests;

use App\Services\Product\ProductCondition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        return [
            'tax_id' => 'integer',
            'supplier_id' => 'integer',
            'manufacturer_id' => 'integer',
            'price' => 'required|numeric|integer',
            'reference' => 'nullable|string|max:255',
            'supplier_reference' => 'nullable|string|max:255',
            'available_for_order' => 'boolean',
            'available_at' => 'sometimes|nullable|date',
            'condition' => [Rule::enum(ProductCondition::class)],
            'name' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'barcodes.*' => 'integer',
            'categories.*' => 'integer',
        ];
    }
}
