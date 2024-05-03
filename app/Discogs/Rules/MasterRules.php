<?php

namespace App\Discogs\Rules;

use App\Validation\Rules;

class MasterRules extends Rules
{
    public function authorize(): bool
    {
        return true;
    }

    public function getRules(): array
    {
        return [
            'master_id' => 'required|integer|numeric',
        ];
    }
}
