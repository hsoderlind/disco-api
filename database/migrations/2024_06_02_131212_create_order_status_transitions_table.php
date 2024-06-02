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
        Schema::create('order_status_transitions', function (Blueprint $table) {
            $table->id();
            ShopMigrationMethods::addShopIdColumn($table);
            $table->unsignedBigInteger('from_status_id');
            $table->unsignedBigInteger('to_status_id');
            $table->timestamps();

            $table->foreign('from_status_id')->references('id')->on('order_statuses')->cascadeOnDelete();
            $table->foreign('to_status_id')->references('id')->on('order_statuses')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_transitions');
    }
};
