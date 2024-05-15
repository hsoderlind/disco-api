<?php

use App\Models\Customer;
use App\Models\Shop;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('person_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('ssn')->nullable();
            $table->string('orgno')->nullable();
            $table->string('vatno')->nullable();
            $table->boolean('taxable')->default(true);
            $table->string('currency')->default('SEK');
            $table->mediumText('note')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_shop', function (Blueprint $table) {
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Shop::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customer_shop');
    }
};
