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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            ShopMigrationMethods::addShopIdColumn($table);
            $table->foreignIdFor(Product::class)->constrained();
            $table->string('sku')->nullable();
            $table->bigInteger('initial_quantity')->default(0);
            $table->bigInteger('reserved_quantity')->nullable();
            $table->bigInteger('sold_quantity')->nullable();
            $table->integer('min_order_quantity')->default(1);
            $table->string('out_of_stock_message')->nullable();
            $table->boolean('allow_order_out_of_stock')->default(false);
            $table->boolean('send_email_out_of_stock')->default(false);
            $table->string('in_stock_message')->nullable();
            $table->dateTime('available_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
