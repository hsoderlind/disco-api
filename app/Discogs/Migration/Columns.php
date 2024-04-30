<?php

namespace App\Discogs\Migration;

use Illuminate\Database\Schema\Blueprint;

abstract class Columns
{
    public static function username(Blueprint $table, string $columnName = 'username')
    {
        $table->string($columnName, 120);
    }

    public static function token(Blueprint $table, string $columnName = 'token')
    {
        $table->text($columnName);
    }

    public static function tokenSecret(Blueprint $table, string $columnName = 'token_secret')
    {
        $table->text($columnName);
    }
}
