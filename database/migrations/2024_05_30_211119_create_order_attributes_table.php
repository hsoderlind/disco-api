<?php

use App\Models\AttributeProduct;
use App\Models\AttributeType;
use App\Models\AttributeValue;
use App\Models\OrderItem;
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
        Schema::create('order_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AttributeProduct::class);
            $table->foreignIdFor(AttributeType::class);
            $table->foreignIdFor(AttributeValue::class);
            $table->foreignIdFor(OrderItem::class);
            $table->string('attribute_type_name');
            $table->string('attribute_value_name');
            $table->bigInteger('price');
            $table->bigInteger('total');
            $table->integer('vat'); // Moms
            $table->integer('tax_value'); // Moms i procent, referens taxes.value
            $table->integer('quantity');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_attributes');
    }
};
