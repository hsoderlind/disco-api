<?php

namespace App\Interfaces;

interface IRules
{
    public function authorize(mixed $user): bool;

    public function shouldValidate(string $requestMethod): bool;

    public function rules(): array;
}
