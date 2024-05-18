<?php

use App\Models\Company;
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
        Schema::table('shops', function (Blueprint $table) {
            $table->string('official_website')->nullable()->after('address_city');
            $table->string('support_website')->nullable()->after('address_city');
            $table->string('terms_of_agreement_url')->nullable()->after('address_city');
            $table->string('privacy_police_url')->nullable()->after('address_city');
            $table->string('support_phone')->nullable()->after('address_city');
            $table->string('support_email')->nullable()->after('address_city');
            $table->foreignIdFor(Company::class)->nullable()->constrained()->after('address_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            Schema::dropColumns('shops', ['official_website', 'support_website', 'terms_of_agreement_url', 'privacy_police_url', 'support_phone', 'support_email']);
        });
    }
};
