<?php

namespace App\Discogs\Rules;

use App\Validation\Rules;

class AddToWantlistRules extends Rules
{
    public function authorize(): bool
    {
        return true;
    }

    public function getRules(): array
    {
        return [
            'release_id' => 'required|numeric|integer',
            'notes' => 'sometimes|string|nullable',
            'rating' => 'sometimes|numeric|integer:max:5',
        ];
    }
}
