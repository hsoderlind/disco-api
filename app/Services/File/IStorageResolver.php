<?php

namespace App\Services\File;

use Illuminate\Http\Request;

interface IStorageResolver
{
    public function getDisk(): string;

    public function getPath(Request $request): string;

    public function getVisibility(): string;
}
