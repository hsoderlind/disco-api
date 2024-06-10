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
        Schema::table('order_total_repositories', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('name')->index()->change();

            $table->unique(['name', 'shop_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('name')->primary()->change();
        });
    }
};
