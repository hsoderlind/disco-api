<?php

namespace App\Services\ProductFile;

use App\Services\File\IFileRules;
use App\Validation\Rules;
use Illuminate\Validation\Rules\File;

class ProductFileRules extends Rules implements IFileRules
{
    public function authorize(): bool
    {
        return $this->request->user()->can('access product');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'POST' || $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [
            'sort_order' => 'required|integer|numeric|min:0',
        ];
    }

    public function getFileRules(): array
    {
        return [
            'product_file' => [
                'required',
                File::types([])
                    ->max(1000000),
            ],
        ];
    }
}
