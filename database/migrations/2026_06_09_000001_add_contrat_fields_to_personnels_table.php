<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            $table->string('type_contrat')->nullable()->after('poste');
            $table->date('date_fin_contrat')->nullable()->after('date_embauche');
        });
    }

    public function down(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            $table->dropColumn(['type_contrat', 'date_fin_contrat']);
        });
    }
};
