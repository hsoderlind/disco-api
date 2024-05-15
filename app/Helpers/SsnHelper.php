<?php

namespace App\Helpers;

use Olssonm\SwedishEntity\Person;

abstract class SsnHelper
{
    public static function format(string $ssn, $separator = true)
    {
        $digits = strpos($ssn, '-') ? strlen($ssn) - 1 : strlen($ssn);

        return (new Person($ssn))->format($digits, $separator);
    }
}
