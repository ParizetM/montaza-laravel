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
        Schema::create('type_expeditions', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->timestamps();
        });
        Schema::create('condition_paiements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->timestamps();
        });
        Schema::create('cdes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('nom');
            $table->foreignId('ddp_cde_statut_id')->constrained(table: 'ddp_cde_statuts');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('entite_id')->constrained('entites'); // entité pour qui on fait la commande
            $table->foreignId('ddp_id')->nullable()->constrained('ddps');
            $table->foreignId('societe_contact_id')->nullable()->constrained('societe_contacts');
            $table->string('affaire_numero')->nullable();
            $table->string('affaire_nom')->nullable();
            $table->string('devis_numero')->nullable();
            $table->foreignId('affaire_suivi_par_id')->nullable()->constrained('users');
            $table->foreignId('acheteur_id')->nullable()->constrained('users');
            $table->integer('tva');
            $table->foreignId('type_expedition_id')->nullable()->constrained('type_expeditions');
            $table->string('adresse_livraison')->nullable();
            $table->string('adresse_facturation')->nullable();
            $table->foreignId('condition_paiement_id')->constrained('condition_paiements');
            $table->boolean('afficher_destinataire')->default(true);
            $table->timestamps();
        });
        Schema::create('cde_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cde_id')->constrained('cdes')->onDelete('cascade');
            $table->integer('poste');
            $table->foreignId('societe_matiere_id')->constrained('societe_matiere');
            $table->integer('quantite');
            $table->date('date_livraison')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cde_lignes');
        Schema::dropIfExists('cdes');
        Schema::dropIfExists('condition_paiements');
        Schema::dropIfExists('type_expeditions');
    }
};
