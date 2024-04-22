<?php

namespace App\Services\Product;

use App\Traits\EnumValues;

enum ProductCondition: string
{
    use EnumValues;

    case New = 'new';
    case Used = 'used';
    case Refurbished = 'refurbished';
}
