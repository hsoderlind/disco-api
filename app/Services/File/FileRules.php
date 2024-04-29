<?php

namespace App\Services\File;

use App\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class FileRules extends Rules
{
    protected IFileRules $validationProvider;

    public function __construct(protected readonly FormRequest $request)
    {
        $fileValidationProvider = new FileValidationProvider();
        $this->validationProvider = $fileValidationProvider->resolve($request);
    }

    public function authorize(): bool
    {
        return $this->validationProvider->authorize();
    }

    public function shouldValidate(): bool
    {
        return $this->validationProvider->shouldValidate();
    }

    public function getRules(): array
    {
        return $this->shouldValidate() ? $this->validationProvider->getFileRules() : [];
    }

    public function getModelRules(): array
    {
        return [
            'id' => 'sometimes|required|exists:files,id',
            'filename' => 'required|string|max:255',
            'extension' => 'required|string|max:255',
            'mimetype' => 'required|string|max:255',
            'size' => 'required|numeric|integer',
        ];
    }
}
