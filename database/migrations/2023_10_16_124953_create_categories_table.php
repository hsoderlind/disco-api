<?php

use App\MigrationMethods\ShopMigrationMethods;
use App\Models\Category;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent')->index();
            $table->unsignedBigInteger('level');
            $table->softDeletes();
            $table->timestamps();
            ShopMigrationMethods::addShopIdColumn($table);
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Product::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_product');
    }
};
