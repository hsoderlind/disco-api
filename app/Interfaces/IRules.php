<?php

namespace App\Interfaces;

interface IRules
{
    public function authorize(): bool;

    public function shouldValidate(): bool;

    public function getRules(): array;
}
