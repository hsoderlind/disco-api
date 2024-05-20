<?php

namespace App\Services\Logotype;

use App\Services\File\FileModelRules;
use App\Services\File\IFileRules;
use App\Traits\RulesMerger;
use App\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class LogotypeRules extends Rules implements IFileRules
{
    use RulesMerger;

    public function authorize(): bool
    {
        return $this->request->user()->can('access shop profile');
    }

    public function shouldValidate(): bool
    {
        return $this->request->getMethod() === 'POST' || $this->request->getMethod() === 'PUT';
    }

    public function getRules(): array
    {
        return [
            'id' => 'sometimes|exists:logotypes,id',
            ...$this->merge('meta', new FileModelRules($this->request), 'required'),
        ];
    }

    public function getFileRules(): array
    {
        return [
            'logotype' => [
                'required',
                File::image()
                    ->max(100000)
                    ->dimensions(Rule::dimensions()->minWidth(150)->minHeight(150)),
            ],
        ];
    }
}
