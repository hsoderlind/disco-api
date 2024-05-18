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
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
            ShopMigrationMethods::addShopIdColumn($table);
            $table->boolean('show_orgnumber')->default(true);
            $table->boolean('show_company_name')->default(true);
            $table->boolean('show_shop_name')->default(false);
            $table->boolean('show_company_official_website')->default(false);
            $table->boolean('show_shop_official_website')->default(false);
            $table->boolean('show_company_support_url')->default(false);
            $table->boolean('show_shop_support_url')->default(false);
            $table->boolean('show_company_terms_of_agreement_url')->default(false);
            $table->boolean('show_shop_terms_of_agreement_url')->default(false);
            $table->boolean('show_company_privacy_police_url')->default(false);
            $table->boolean('show_shop_privacy_police_url')->default(false);
            $table->boolean('show_company_support_phone')->default(true);
            $table->boolean('show_shop_support_phone')->default(true);
            $table->boolean('show_company_support_email')->default(true);
            $table->boolean('show_shop_support_email')->default(true);
            $table->boolean('show_support_address')->default(false);
            $table->boolean('show_shop_address')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_settings');
    }
};
