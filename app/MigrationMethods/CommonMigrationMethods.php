<?php

namespace App\MigrationMethods;

use Illuminate\Database\Schema\Blueprint;

abstract class CommonMigrationMethods
{
    public static function addSortOrderColumn(Blueprint $table, string $column = 'sort_order')
    {
        $table->unsignedBigInteger($column)->default(0);
    }

    public static function addActiveColumn(Blueprint $table, string $column = 'active', bool $default = true)
    {
        $table->boolean($column)->default($default);
    }
}
