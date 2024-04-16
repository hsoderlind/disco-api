<?php

namespace App\Services\File;

use App\Services\ProductImage\ProductImageRules;
use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;
use LogicException;
use RuntimeException;

class FileValidationProvider
{
    protected $providers = [
        'product_image' => ProductImageRules::class,
    ];

    public function resolve(FormRequest $request): IFileRules
    {
        $request->validate([
            'input_name' => 'required|string',
        ]);

        $inputName = $request->input('input_name');
        $provider = $this->providers[$inputName];

        if (! isset($provider)) {
            throw new InvalidArgumentException($inputName.' has no corresponding provider for rules');
        }

        if (! is_string($provider)) {
            throw new LogicException('$provider must be a string.');
        }

        if (! class_exists($provider)) {
            throw new RuntimeException($provider.' is not a class.');
        }

        $class = new $provider($request);

        if (! ($class instanceof IFileRules)) {
            throw new LogicException($provider.' must implement the IFileRules interface.');
        }

        return $class;
    }
}
