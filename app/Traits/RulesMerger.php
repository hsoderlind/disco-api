<?php

namespace App\Traits;

use App\Interfaces\IRules;
use Illuminate\Support\Str;

trait RulesMerger
{
    protected function merge(
        string $key,
        IRules $rulesClass,
        ?string $additionRules = '',
        bool $isArray = false,
        bool $checkArrayKeys = true,
        ?array $omitFields = []
    ): array {
        $rules = $rulesClass->getRules();

        if (count($omitFields)) {
            $omitFieldsFlipped = array_flip($omitFields);
            $rules = array_diff_key($rules, $omitFieldsFlipped);
        }

        $joinedFields = implode(',', array_keys($rules));

        if ($additionRules !== '' && ! Str::endsWith($additionRules, '|')) {
            $additionRules .= '|';
        }

        $array = [];

        if ($checkArrayKeys) {
            $array[$key.($isArray ? '.*' : '')] = $additionRules.'array:'.$joinedFields;
        }

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
