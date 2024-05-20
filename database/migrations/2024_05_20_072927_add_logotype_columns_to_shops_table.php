<?php

use App\Models\Logotype;
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
            $table->foreignIdFor(Logotype::class, 'default_logotype_id')->nullable()->constrained('logotypes');
            $table->foreignIdFor(Logotype::class, 'mini_logotype_id')->nullable()->constrained('logotypes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            //
        });
    }
};
