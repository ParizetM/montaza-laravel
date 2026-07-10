<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnel_absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade');
            $table->date('date');
            $table->enum('type', ['retard', 'absence', 'maladie', 'autre'])->default('absence');
            $table->enum('duree', ['demi_journee', 'journee_complete'])->nullable();
            $table->unsignedSmallInteger('minutes_retard')->nullable()->comment('Uniquement pour le type retard');
            $table->text('motif')->nullable();
            $table->enum('statut', ['en_attente', 'justifie', 'non_justifie'])->default('en_attente');
            $table->timestamps();

            $table->index(['personnel_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnel_absences');
    }
};
