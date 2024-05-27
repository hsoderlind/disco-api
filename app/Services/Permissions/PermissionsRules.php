<?php

namespace App\Services\Permissions;

use App\Validation\Rules;

class PermissionsRules extends Rules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access shop permission');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() == 'POST' || $this->request->getMethod() == 'PUT';
    }

    public function getRules(): array
    {
        return [
            'roleId' => 'required|exists:roles,id',
            'permissions.*' => 'required|string',
        ];
    }
}
