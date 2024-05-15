<?php

namespace App\Helpers;

use Olssonm\SwedishEntity\Organization;

abstract class OrgNumberHelper
{
    public static function deformat(string $orgnumber): string
    {
        return (new Organization($orgnumber))->format(false);
    }

    public static function format(string $orgnumber): string
    {
        return (new Organization($orgnumber))->format();
    }
}
