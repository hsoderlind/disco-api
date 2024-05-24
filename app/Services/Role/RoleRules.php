<?php

namespace App\Services\Role;

use App\Validation\Rules;

class RoleRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access shop team');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'POST' || $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [
            'id' => 'sometimes|required|exists:roles,id',
            'name' => 'required|string|max:255',
        ];
    }
}
