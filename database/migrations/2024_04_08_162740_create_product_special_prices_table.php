<?php

use App\MigrationMethods\ShopMigrationMethods;
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
        Schema::create('product_special_prices', function (Blueprint $table) {
            $table->id();
            ShopMigrationMethods::addShopIdColumn($table);
            $table->foreignIdFor(Product::class)->constrained();
            $table->unsignedBigInteger('special_price');
            $table->dateTime('entry_date');
            $table->dateTime('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_special_prices');
    }
};
