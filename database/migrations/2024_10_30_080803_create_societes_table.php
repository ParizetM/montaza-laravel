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
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text(column: 'contenu')->nullable();
        });
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'nom', length: 100);
        });
        Schema::create('forme_juridiques', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'code', length: 10);
            $table->string(column: 'nom', length: 100);
        });
        Schema::create('code_apes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'code', length: 10);
            $table->string(column: 'nom');
        });
        Schema::create('societe_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'nom', length: 100);
        });
        Schema::create('societes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'raison_sociale', length: 100);
            $table->integer(column: 'siren', autoIncrement: false);
            $table->foreignId('forme_juridique_id')->constrained('forme_juridiques');
            $table->foreignId('code_ape_id')->constrained('code_apes');
            $table->foreignId('societe_type_id')->constrained('societe_types');
            $table->string('telephone', length: 20);
            $table->string('email', length: 100);
            $table->string('site_web', length: 100)->nullable();
            $table->string('numero_tva', length: 100);
            $table->foreignId('commentaire_id')->constrained('commentaires')->nullable();
            $table->softDeletes();
        });
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'nom', length: 100);
            $table->string(column: 'adresse', length: 100);
            $table->string(column: 'code_postal', length: 10);
            $table->string(column: 'ville', length: 100);
            $table->string(column: 'region', length: 100);
            $table->foreignId('pay_id')->constrained('pays');
            $table->string('siret', length: 14);
            $table->foreignId('societe_id')->constrained('societes');
            $table->foreignId('commentaire_id')->constrained('commentaires');
            $table->softDeletes();
        });
        Schema::create('societe_contacts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'nom', length: 100);
            $table->string(column: 'prenom', length: 100);
            $table->string(column: 'fonction', length: 100);
            $table->string(column: 'email', length: 100);
            $table->string(column: 'telephone_fixe', length: 20);
            $table->string(column: 'telephone_portable', length: 20);
            $table->foreignId('etablissement_id')->constrained('etablissements');
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('societe_contacts');
        Schema::dropIfExists('etablissements');
        Schema::dropIfExists('societes');
        Schema::dropIfExists('code_apes');
        Schema::dropIfExists('forme_juridiques');
        Schema::dropIfExists('societe_types');
        Schema::dropIfExists('pays');
    }
};
