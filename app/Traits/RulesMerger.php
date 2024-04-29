<?php

namespace App\Traits;

use App\Interfaces\IRules;
use Illuminate\Support\Str;

trait RulesMerger
{
    protected function merge(string $key, IRules $rulesClass, ?string $additionRules = '', bool $isArray = false): array
    {
        $rules = $rulesClass->getRules();
        $joinedFields = implode(',', array_keys($rules));

        if ($additionRules !== '' && ! Str::endsWith($additionRules, '|')) {
            $additionRules .= '|';
        }

        $array = [$key.($isArray ? '.*' : '') => $additionRules.'array:'.$joinedFields];

        foreach ($rules as $field => $rule) {
            $array[$key.'.'.($isArray ? '*.' : '').$field] = $rule;
        }

        return $array;
    }

    protected function mergeWithRules(string $key, array $rules, ?string $additionRules = '', ?bool $isArray = false): array
    {
        $joinedFields = implode(',', $rules);

        if ($additionRules !== '' && ! Str::endsWith($additionRules, '|')) {
            $additionRules .= '|';
        }

        $array = [$key.($isArray ? '.*' : '') => $additionRules.'array:'.$joinedFields];

        foreach ($rules as $field => $rule) {
            $array[$key.'.'.($isArray ? '*.' : '').$field] = $rule;
        }

        return $array;
    }
}
