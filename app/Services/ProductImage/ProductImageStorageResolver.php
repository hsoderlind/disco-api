<?php

namespace App\Services\ProductImage;

use App\Services\File\IStorageResolver;
use App\Services\Shop\ShopSession;
use Illuminate\Http\Request;

class ProductImageStorageResolver implements IStorageResolver
{
    public function getDisk(): string
    {
        return 'local';
    }

    public function getPath(Request $request): string
    {
        return 'product-images/'.ShopSession::getId();
    }

    public function getVisibility(): string
    {
        return 'private';
    }
}
