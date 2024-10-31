<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDatabase extends Command
{
    protected $signature = 'db:reset-database';
    protected $description = 'supprime toutes les tables de la base de données et les re créer';

    public function handle(): void
    {
        // Désactiver les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $tables = DB::select('SHOW TABLES');

        // Supprimer toutes les tables
        foreach ($tables as $table) {
            $tableName = $table->{"Tables_in_" . env('DB_DATABASE')}; // Récupère le nom de la table
            DB::statement("DROP TABLE IF EXISTS {$tableName}");
            $this->info("Table '{$tableName}' supprimée.");
        }

        // Réactiver les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Toutes les tables ont été supprimées.');
        $this->call('migrate');
        $this->info('Toutes les tables ont été recréées.');
        $this->call('db:seed');
        $this->info('Toutes les tables ont été remplies avec des données.');
    }
}
