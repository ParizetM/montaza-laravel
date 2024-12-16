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
        Schema::create('unites', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->timestamps();
        });
        Schema::create('familles', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->timestamps();
        });
        Schema::create('sous_familles', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->foreignId('famille_id')->constrained('familles');
            $table->timestamps();
        });
        Schema::create('matieres', function (Blueprint $table) {
            $table->id();
            $table->string('ref_interne')->unique();
            $table->string('ref_externe');
            $table->string('designation');
            $table->foreignId('societe_id')->constrained('societes');
            $table->foreignId('unite_id')->constrained('unites');
            $table->foreignId('sous_famille_id')->constrained('sous_familles');
            $table->integer('dn');
            $table->float('epaisseur');
            $table->decimal('dernier_prix', 8, 2);
            $table->decimal('prix_moyen', 8, 2);
            $table->date('date_dernier_achat');
            $table->integer('quantite');
            $table->integer('stock_min');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matieres');
        Schema::dropIfExists('sous_familles');
        Schema::dropIfExists('familles');
        Schema::dropIfExists('unites');
    }
};
