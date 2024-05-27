<?php

namespace App\Http\Requests;

use App\Services\Permissions\PermissionsRules;
use App\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    protected PermissionsRules $rules;

    protected function prepareForValidation()
    {
        $this->rules = new PermissionsRules($this);
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
