<?php

namespace App\Services\Note;

use App\Validation\Rules;

class NoteRules extends Rules
{
    public function authorize(): bool
    {
        return true;
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'POST' || $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [
            'id' => 'sometimes|required|exists:notes,id',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
        ];
    }
}
