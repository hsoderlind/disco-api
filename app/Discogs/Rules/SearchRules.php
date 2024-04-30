<?php

namespace App\Discogs\Rules;

use App\Discogs\Enums\SearchType;
use App\Validation\Rules;
use Illuminate\Validation\Rule;

class SearchRules extends Rules
{
    public function authorize(): bool
    {
        return true;
    }

    public function getRules(): array
    {
        return [
            'query' => 'sometimes|string',
            'type' => [
                'sometimes',
                Rule::enum(SearchType::class),
            ],
            'title' => 'sometimes|string',
            'release_title' => 'sometimes|string',
            'credit' => 'sometimes|string',
            'artist' => 'sometimes|string',
            'anv' => 'sometimes|string',
            'label' => 'sometimes|string',
            'genre' => 'sometimes|string',
            'style' => 'sometimes|string',
            'country' => 'sometimes|string',
            'year' => 'sometimes|string',
            'format' => 'sometimes|string',
            'catno' => 'sometimes|string',
            'barcode' => 'sometimes|string',
            'track' => 'sometimes|string',
            'submitter' => 'sometimes|string',
            'contributor' => 'sometimes|string',
            'page' => 'sometimes|integer|numeric',
            'per_page' => 'sometimes|integer|numeric',
        ];
    }
}
