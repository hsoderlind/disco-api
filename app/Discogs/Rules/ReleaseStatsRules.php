<?php

namespace App\Discogs\Rules;

use App\Validation\Rules;

class ReleaseStatsRules extends Rules
{
    public function authorize(): bool
    {
        return true;
    }

    public function getRules(): array
    {
        return [
            'release_id' => 'required|integer|numeric',
        ];
    }
}
