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
        Schema::create('mouvement_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Qui a fait l'action
            $table->string('type'); // "entree" ou "sortie"
            $table->integer('quantite');
            $table->decimal('valeur_unitaire', 8, 3);
            $table->string('raison')->nullable(); // Explication du mouvement
            $table->timestamp('date')->useCurrent();
            $table->timestamps();
        });


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_stocks');
    }
};
