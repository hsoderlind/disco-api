<?php

namespace App\Services\Product;

use App\Traits\EnumValues;

enum ProductState: string
{
    use EnumValues;

    case Draft = 'draft';
    case Published = 'published';
}
