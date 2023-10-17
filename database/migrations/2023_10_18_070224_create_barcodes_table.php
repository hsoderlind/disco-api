<?php

use App\MigrationMethods\ShopMigrationMethods;
use App\Models\Barcode;
use App\Models\BarcodeType;
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
        Schema::create('barcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BarcodeType::class)->constrained();
            $table->string('value', 255);
            $table->softDeletes();
            $table->timestamps();
            ShopMigrationMethods::addShopIdColumn($table);
        });

        Schema::create('barcode_product', function (Blueprint $table) {
            $table->foreignIdFor(Barcode::class);
            $table->foreignIdFor(Product::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barcodes');
        Schema::dropIfExists('barcode_product');
    }
};
