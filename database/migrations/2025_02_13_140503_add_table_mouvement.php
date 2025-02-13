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
        Schema::create('matiere_mouvements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained()->cascadeOnDelete();
            $table->integer('quantite');
            $table->boolean('type_mouvement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matiere_mouvements');
    }
};
