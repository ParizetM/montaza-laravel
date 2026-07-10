<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personnel_absences', function (Blueprint $table) {
            // Précise si une demi-journée est le matin ou l'après-midi
            $table->enum('periode', ['matin', 'apres_midi'])->nullable()->after('duree');
        });
    }

    public function down(): void
    {
        Schema::table('personnel_absences', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
    }
};
