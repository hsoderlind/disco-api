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
        Schema::create('shipping_method_repositories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            ShopMigrationMethods::addShopIdColumn($table);
            $table->string('title');
            $table->mediumText('description')->nullable();
            $table->json('configuration')->nullable();
            $table->integer('fee');
            $table->string('component');
            $table->string('admin_component')->nullable();
            CommonMigrationMethods::addSortOrderColumn($table);
            CommonMigrationMethods::addActiveColumn($table);
            $table->string('control_class');
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
        Schema::dropIfExists('shipping_method_repositories');
    }
};
