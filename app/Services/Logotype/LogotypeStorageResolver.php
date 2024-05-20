<?php

namespace App\Services\Logotype;

use App\Services\File\IStorageResolver;
use App\Services\Shop\ShopSession;
use Illuminate\Http\Request;

class LogotypeStorageResolver implements IStorageResolver
{
    public function getDisk(): string
    {
        return 'local';
    }

    public function getPath(Request $request): string
    {
        return 'logotype/'.ShopSession::getId();
    }

    public function getVisibility(): string
    {
        return 'private';
    }
}
