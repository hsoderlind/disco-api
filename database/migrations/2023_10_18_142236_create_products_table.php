<?php

use App\MigrationMethods\ShopMigrationMethods;
use App\Models\Manufacturer;
use App\Models\Supplier;
use App\Models\Tax;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tax::class)->constrained();
            $table->foreignIdFor(Supplier::class)->constrained();
            $table->foreignIdFor(Manufacturer::class)->constrained();
            $table->unsignedBigInteger('price');
            $table->string('reference', 255)->nullable();
            $table->string('supplier_reference', 255)->nullable();
            $table->boolean('available_for_order')->default(true);
            $table->dateTime('available_at');
            $table->enum('condition', ['new', 'used', 'refurbished']);
            $table->string('name', 255);
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('products');
    }
};
