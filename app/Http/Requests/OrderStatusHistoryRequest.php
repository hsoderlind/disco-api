<?php

namespace App\Http\Requests;

use App\Services\Orders\OrderStatusHistoryRules;
use App\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class OrderStatusHistoryRequest extends FormRequest
{
    protected string $rulesClass = OrderStatusHistoryRules::class; // Class name of the rules class

    protected $rules;

    protected function prepareForValidation()
    {
        $this->rules = new $this->rulesClass($this);
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
