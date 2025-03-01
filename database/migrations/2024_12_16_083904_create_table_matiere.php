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
            $table->string('short')->unique();
            $table->string('full');
            $table->string('full_plural')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
        Schema::create('familles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->timestamps();
        });
        Schema::create('sous_familles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->foreignId('famille_id')->constrained('familles');
            $table->timestamps();
        });
        Schema::create('dossier_standards', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->timestamps();
        });
        Schema::create('standards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_standard_id')->constrained('dossier_standards')->cascadeOnDelete();
            $table->string('nom');

            $table->timestamps();
        });

        Schema::create('standard_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standard_id')->constrained('standards')->cascadeOnDelete();
            $table->string('version');
            $table->string('chemin_pdf'); // Chemin du fichier PDF
            $table->timestamps();
        });
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->timestamps();
        });
        Schema::create('matieres', function (Blueprint $table) {
            $table->id();
            $table->string('ref_interne')->unique();
            $table->foreignId('standard_version_id')->nullable()->constrained('standard_versions');
            $table->string('designation');
            $table->foreignId('unite_id')->constrained('unites');
            $table->foreignId(column: 'sous_famille_id')->constrained('sous_familles');
            $table->foreignId(column: 'material_id')->nullable()->constrained('materials');
            $table->string('dn')->nullable();
            $table->string('epaisseur')->nullable();
            $table->decimal('prix_moyen', 8, 2)->nullable();
            $table->integer('quantite');
            $table->integer('stock_min');
            $table->date('date_dernier_achat')->nullable();
            $table->timestamps();
        });
        Schema::create('societe_matiere', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->foreignId('societe_id')->constrained('societes')->cascadeOnDelete();
            $table->string('ref_fournisseur')->nullable(); // Référence fournisseur
            $table->string('designation_fournisseur')->nullable(); // Désignation spécifique au fournisseur
            $table->decimal('prix', 8, 2); // Prix associé
            $table->foreignId('unite_id')->nullable()->constrained('unites');
            $table->dateTime('date_dernier_prix'); // Date du dernier prix
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standard_versions');
        Schema::dropIfExists('standards');
        Schema::dropIfExists('societe_matiere');
        Schema::dropIfExists('matieres');
        Schema::dropIfExists('sous_familles');
        Schema::dropIfExists('familles');
        Schema::dropIfExists('unites');
    }
};
