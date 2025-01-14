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
        Schema::create('ddp_cde_statuts', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('couleur');
            $table->string('couleur_texte');
            $table->timestamps();
        });

        Schema::create('ddps', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('nom');
            $table->foreignId('ddp_cde_statut_id')->constrained(table: 'ddp_cde_statuts');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
        Schema::create('ddp_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ddp_id')->constrained('ddps')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres');
            $table->integer('quantite');
            $table->timestamps();
        });
        Schema::create('ddp_ligne_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ddp_ligne_id')->constrained('ddp_lignes')->onDelete('cascade');
            $table->foreignId('societe_id')->constrained('societes');
            $table->foreignId('ddp_cde_statut_id')->constrained(table: 'ddp_cde_statuts');
            $table->foreignId('societe_contact_id')->nullable()->constrained('societe_contacts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ddp_ligne_fournisseur');
        Schema::dropIfExists('ddp_lignes');
        Schema::dropIfExists('ddps');
        Schema::dropIfExists('ddp_cde_statuts');
    }
};
