<?php

namespace App\Validation;

use App\Interfaces\IRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Rules implements IRules
{
    public function __construct(protected readonly FormRequest $request)
    {

    }

    public function authorize(): bool
    {
        return false;
    }

    public function shouldValidate(): bool
    {
        return true;
    }

    public function getRules(): array
    {
        return [];
    }

    public function sometimes(array $rules): array
    {
        $newRules = [];

        foreach ($rules as $key => $value) {
            $newRules[$key] = $this->prependSometimes($value);
        }

        return $newRules;
    }

    private function prependSometimes(array|string $conditions): array|string
    {
        if (is_array($conditions) && ! in_array('sometimes', $conditions)) {
            $conditions = Arr::prepend($conditions, 'sometimes');

            return $conditions;
        }

        if (! Str::contains($conditions, 'sometimes')) {
            $conditions = 'sometimes|'.$conditions;

            return $conditions;
        }
    }
}
