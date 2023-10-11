<?php

namespace App\Helpers;

abstract class OrgNumberHelper
{
    public static function deformat(string $orgnumber): string
    {
        return str_replace([' ', '-'], '', $orgnumber);
    }

    public static function format(string $orgnumber): string
    {
        return substr($orgnumber, 0, 6).'-'.substr($orgnumber, 6);
    }
}
