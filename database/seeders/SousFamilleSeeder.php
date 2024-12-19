<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SousFamilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sousFamilles = [
            ['nom' => 'électrique', 'famille_id' => 1],
            ['nom' => 'mécanique', 'famille_id' => 1],
            ['nom' => 'Acier', 'famille_id' => 2],
            ['nom' => 'Aluminium', 'famille_id' => 2],
            ['nom' => 'Cuivre', 'famille_id' => 2],
            ['nom' => 'PVC', 'famille_id' => 2],
            ['nom' => 'Visserie', 'famille_id' => 3],
            ['nom' => 'Confort', 'famille_id' => 3],
            ['nom' => 'Nettoyage', 'famille_id' => 3],
            ['nom' => 'EPI', 'famille_id' => 3],
            ['nom' => 'Autre', 'famille_id' => 3],
        ];

        foreach ($sousFamilles as $sousFamille) {
            \App\Models\SousFamille::create($sousFamille);
        }
    }
}
