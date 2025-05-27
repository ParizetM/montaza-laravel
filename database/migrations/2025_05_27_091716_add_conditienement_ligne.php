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
        Schema::table('cde_lignes', function (Blueprint $table) {
            $table->decimal('conditionnement', 8, 2)->default(0)->after('quantite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cde_lignes', function (Blueprint $table) {
            $table->dropColumn('conditionnement');
        });
    }
};
