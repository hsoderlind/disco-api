<?php

use App\Discogs\Migration\Columns;
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
        Schema::create('discogs_tokens', function (Blueprint $table) {
            $table->id();
            ShopMigrationMethods::addShopIdColumn($table);
            Columns::username($table);
            Columns::token($table);
            Columns::tokenSecret($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discogs_tokens');
    }
};
