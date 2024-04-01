<?php

use App\MigrationMethods\ShopMigrationMethods;
use App\Models\AttributeProduct;
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
        Schema::create('attribute_stocks', function (Blueprint $table) {
            $table->id();
            ShopMigrationMethods::addShopIdColumn($table);
            $table->foreignIdFor(AttributeProduct::class)->constrained();
            $table->string('sku')->nullable();
            $table->string('stock_unit')->nullable();
            $table->string('out_of_stock_message')->nullable();
            $table->dateTime('available_at')->nullable();
            $table->boolean('allow_order_out_of_stock')->default(false);
            $table->bigInteger('initial_quantity')->default(0);
            $table->bigInteger('reserved_quantity')->nullable();
            $table->bigInteger('sold_quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_stocks');
    }
};
