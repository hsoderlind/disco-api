<?php

use App\MigrationMethods\CommonMigrationMethods;
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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->string('name')->primary();
            ShopMigrationMethods::addShopIdColumn($table);
            $table->string('title');
            $table->mediumText('description')->nullable();
            CommonMigrationMethods::addSortOrderColumn($table);
            CommonMigrationMethods::addActiveColumn($table);
            $table->bigInteger('fee')->nullable();
            $table->string('control_class');
            $table->string('component');
            $table->string('admin_component')->nullable();
            $table->json('configuration')->nullable();
            $table->string('version');
            $table->timestamps();

            $table->unique(['name', 'shop_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
