<?php

namespace App\Validation;

use App\Interfaces\IRules;
use Illuminate\Foundation\Http\FormRequest;

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
}
