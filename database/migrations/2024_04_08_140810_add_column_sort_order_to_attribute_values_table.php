<?php

use App\MigrationMethods\CommonMigrationMethods;
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
        Schema::table('attribute_values', function (Blueprint $table) {
            CommonMigrationMethods::addSortOrderColumn($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_values', function (Blueprint $table) {
            CommonMigrationMethods::dropSortOrderColumn($table);
        });
    }
};
