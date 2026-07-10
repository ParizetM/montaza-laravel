<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer l'ancienne contrainte CHECK sur le type
        DB::statement('ALTER TABLE personnel_absences DROP CONSTRAINT IF EXISTS personnel_absences_type_check');

        // Ajouter la nouvelle contrainte avec les valeurs correctes
        DB::statement("ALTER TABLE personnel_absences ADD CONSTRAINT personnel_absences_type_check CHECK (type IN ('retard', 'absence', 'maladie', 'autre'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE personnel_absences DROP CONSTRAINT IF EXISTS personnel_absences_type_check');
        DB::statement("ALTER TABLE personnel_absences ADD CONSTRAINT personnel_absences_type_check CHECK (type IN ('retard', 'absence_injustifiee', 'absence_justifiee', 'maladie', 'autre'))");
    }
};
