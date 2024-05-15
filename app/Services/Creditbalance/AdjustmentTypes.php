<?php

namespace App\Services\CreditBalance;

use App\Traits\EnumValues;

enum AdjustmentType: string
{
    use EnumValues;

    case Credit = 'credit';
    case Debet = 'debet';
}
