<?php

use App\MigrationMethods\CommonMigrationMethods;
use App\MigrationMethods\ShopMigrationMethods;
use App\Models\AttributeType;
use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attribute_products', function (Blueprint $table) {
            $table->id();
            ShopMigrationMethods::addShopIdColumn($table);
            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(AttributeType::class)->constrained();
            $table->foreignIdFor(AttributeValue::class)->constrained();
            CommonMigrationMethods::addSortOrderColumn($table);
            CommonMigrationMethods::addActiveColumn($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_products');
    }
};
