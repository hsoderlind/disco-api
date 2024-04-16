<?php

namespace App\Services\File;

use App\Interfaces\IRules;

interface IFileRules extends IRules
{
    public function getFileRules(): array;
}
