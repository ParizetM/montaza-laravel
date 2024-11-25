<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetTables extends Command
{
    protected $signature = 'db:reset-tables';
    protected $description = 'Vide toutes les tables de la base de données et les re créer';

    public function handle(): void
    {
        // Désactiver les contraintes de clé étrangère
        DB::statement('SET session_replication_role = replica;');

        $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public';");
        foreach ($tables as $table) {
            $tableName = $table->tablename; // Récupère le nom de la table

            if ($tableName === 'migrations') {
                continue; // Ignorer la table migrations
            }

            DB::table($tableName)->truncate();
            $this->info("Table '{$tableName}' vidée.");
        }

        // Réactiver les contraintes de clé étrangère
        DB::statement('SET session_replication_role = DEFAULT;');

        $this->info('Toutes les tables ont été vidées.');
        $this->call('db:seed');
        $this->info('Toutes les tables ont été réinitialisé.');
    }
}
