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
        Schema::create('order_settings', function (Blueprint $table) {
            $table->id();
            ShopMigrationMethods::addShopIdColumn($table);
            $table->string('order_number_prefix')->nullable();
            $table->string('order_number_suffix')->nullable();
            $table->integer('order_number_start')->default(1);
            $table->integer('order_number_serial_length')->default(6);
            $table->string('order_number_pattern')->default('[prefix]-[serial]-[suffix]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_settings');
    }
};
