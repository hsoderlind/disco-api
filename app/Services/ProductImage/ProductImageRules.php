<?php

namespace App\Services\ProductImage;

use App\Services\File\FileModelRules;
use App\Services\File\IFileRules;
use App\Traits\RulesMerger;
use App\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProductImageRules extends Rules implements IFileRules
{
    use RulesMerger;

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
            'id' => 'sometimes|required|exists:product_images,id',
            'use_as_cover' => 'boolean',
            'sort_order' => 'integer|numeric|min:0',
            ...$this->merge('meta', new FileModelRules($this->request), 'required'),
        ];
    }

    public function getFileRules(): array
    {
        return [
            'product_image' => [
                'required',
                File::image()
                    ->max(100000)
                    ->dimensions(Rule::dimensions()->minWidth(150)->minHeight(150)),
            ],
        ];
    }
}
