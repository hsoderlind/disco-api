<?php

namespace App\Services\CreditBalance;

use App\Traits\EnumValues;

enum AdjustmentTypes: string
{
    use EnumValues;

    case Credit = 'credit';
    case Debet = 'debet';
}
