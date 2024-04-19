<?php

namespace App\Services\ProductFile;

use App\Services\File\IStorageResolver;
use App\Services\Shop\ShopSession;
use Illuminate\Http\Request;

class ProductFileStorageResolver implements IStorageResolver
{
    public function getDisk(): string
    {
        return 'local';
    }

    public function getPath(Request $request): string
    {
        return 'product-file/'.ShopSession::getId();
    }

    public function getVisibility(): string
    {
        return 'private';
    }
}
