<?php

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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number_prefix')->nullable()->after('customer_id');
            $table->integer('order_number_serial')->after('customer_id');
            $table->string('order_number_suffix')->nullable()->after('customer_id');
            $table->string('order_number')->after('customer_id');
            // $table->integer('order_number_serial_length')->default(6);
            // $table->string('order_number_pattern')->default('[prefix]-[serial]-[suffix]');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_number');
        });
    }
};
