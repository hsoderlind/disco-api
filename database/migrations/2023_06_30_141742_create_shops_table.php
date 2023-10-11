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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('orgnumber');
            $table->string('url_alias');
            $table->string('address_street1');
            $table->string('address_street2')->nullable();
            $table->string('address_zip');
            $table->string('address_city');
            $table->unsignedBigInteger('account_owner');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('shop_user', function (Blueprint $table) {
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->index(['shop_id', 'user_id'], 'shop_user_shop_id_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_user');
        Schema::dropIfExists('shops');
    }
};
