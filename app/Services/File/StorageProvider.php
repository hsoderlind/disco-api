<?php

namespace App\Services\File;

use App\Services\ProductImage\ProductImageStorageResolver;
use Illuminate\Http\Request;
use InvalidArgumentException;
use LogicException;
use RuntimeException;

/**
 * @property-read string $inputName
 */
class StorageProvider
{
    protected string $inputName;

    protected $providers = [
        'product_image' => ProductImageStorageResolver::class,
    ];

    public function __construct(?Request $request = null, ?string $inputName = null)
    {
        if (! is_null($request)) {
            $request->validate([
                'storage_provider' => 'required|string',
            ]);
            $this->inputName = $request->input('storage_provider') ?? $request->query('storage_provider');
        } elseif (is_string($inputName)) {
            $this->inputName = $inputName;
        } else {
            throw new InvalidArgumentException('$request or $inputName argument must be provided.');
        }

    }

    public function resolve(): IStorageResolver
    {
        $provider = $this->providers[$this->inputName];

        if (! isset($provider)) {
            throw new InvalidArgumentException($this->inputName.' has no corresponding provider for rules');
        }

        if (! is_string($provider)) {
            throw new LogicException('$provider must be a string.');
        }

        if (! class_exists($provider)) {
            throw new RuntimeException($provider.' is not a class.');
        }

        $resolver = new $provider();

        if (! ($resolver instanceof IStorageResolver)) {
            throw new LogicException($provider.' must implement the IFileRules interface.');
        }

        return $resolver;
    }

    public function __get($name)
    {
        if (! property_exists($this, $name)) {
            throw new RuntimeException('There in no property '.$name.' on class '.__CLASS__);
        }

        return $this->$name;
    }
}
