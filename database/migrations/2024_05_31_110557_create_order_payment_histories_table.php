<?php

use App\Models\OrderPayment;
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
        Schema::create('order_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderPayment::class);
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payment_histories');
    }
};
