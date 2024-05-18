<?php

use App\Models\Account;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('orgnumber');
            $table->string('official_website')->nullable();
            $table->string('support_website')->nullable();
            $table->string('terms_of_agreement_url')->nullable();
            $table->string('privacy_police_url')->nullable();
            $table->string('support_phone')->nullable();
            $table->string('support_email')->nullable();
            $table->foreignIdFor(Account::class, 'support_address_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
