<?php

namespace App\Discogs\Rules;

use App\Validation\Rules;
use Illuminate\Validation\Rule;

class MasterReleaseVersionsRules extends Rules
{
    public function authorize(): bool
    {
        return true;
    }

    public function getRules(): array
    {
        return [
            'master_id' => 'required|integer|numeric',
            'page' => 'sometimes|integer|numeric',
            'per_page' => 'sometimes|integer|numeric',
            'format' => 'sometimes|string',
            'label' => 'sometimes|string',
            'released' => 'sometimes|integer|numeric',
            'country' => 'sometimes|string',
            'sort' => [
                'sometimes',
                Rule::enum(['released', 'title', 'format', 'label', 'catno', 'country']),
            ],
            'sort_order' => [
                'sometimes',
                Rule::enum(['asc', 'desc']),
            ],
        ];
    }
}
