<?php

namespace App\Discogs\Rules;

use App\Validation\Rules;

class MarketplaceRules extends Rules
{
    public function authorize(): bool
    {
        return true;
    }

    public function getRules(): array
    {
        return [
            'release_id' => 'required|numeric|integer',
            'curr_abbr' => 'sometimes|string',
        ];
    }
}
