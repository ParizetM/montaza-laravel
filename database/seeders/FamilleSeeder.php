<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $familles = [
            ['nom' => 'outil'],
            ['nom' => 'matière'],
            ['nom' => 'consommable'],
        ];

        foreach ($familles as $famille) {
            \App\Models\Famille::create($famille);
        }
    }
}
