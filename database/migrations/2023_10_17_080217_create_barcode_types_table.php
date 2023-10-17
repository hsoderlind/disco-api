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
        Schema::create('barcode_types', function (Blueprint $table) {
            $table->id();
            $table->string('label', 255);
            $table->boolean('active')->default(false);
            $table->softDeletes();
            $table->timestamps();
            ShopMigrationMethods::addShopIdColumn($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barcode_types');
    }
};
