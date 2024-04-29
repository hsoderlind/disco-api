<?php

namespace App\Services\File;

use App\Validation\Rules;

class FileModelRules extends Rules
{
    public function getRules(): array
    {
        return [
            'id' => 'sometimes|required|exists:files,id',
            'filename' => 'required|string|max:255',
            'extension' => 'required|string|max:255',
            'mimetype' => 'required|string|max:255',
            'size' => 'required|numeric|integer',
            'storage_resolver' => 'required|string|max:255',
        ];
    }
}
