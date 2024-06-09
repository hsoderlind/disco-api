<?php

use App\MigrationMethods\ShopMigrationMethods;
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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            ShopMigrationMethods::addShopIdColumn($table);
            $table->morphs('receiptable');
            $table->integer('receipt_number');
            $table->unsignedBigInteger('cashier_id');
            $table->timestamps();

            $table->unique(['shop_id', 'receipt_number']);
            $table->foreign('cashier_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
