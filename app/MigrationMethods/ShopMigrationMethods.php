<?php

namespace App\MigrationMethods;

use App\Models\Shop;
use Illuminate\Database\Schema\Blueprint;

abstract class ShopMigrationMethods
{
    public static function addShopIdColumn(Blueprint $table): void
    {
        $table->foreignIdFor(Shop::class)->constrained();
    }
}
