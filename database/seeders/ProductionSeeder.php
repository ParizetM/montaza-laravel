<?php

namespace Database\Seeders;

use App\Models\Affaire;
use App\Models\Materiel;
use App\Models\Reparation;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer 20 matériels
        $materiels = Materiel::factory()->count(20)->create();

        // Créer 10 affaires
        $affaires = Affaire::factory()->count(10)->create()->each(function ($affaire) use ($materiels) {
            // Associer aléatoirement 1 à 5 matériels à chaque affaire
            $affaire->materiels()->attach(
                $materiels->random(rand(1, 5))->pluck('id')->toArray(),
                [
                    'date_debut' => now(),
                    'statut' => 'reserve'
                ]
            );

            // Créer des commandes liées à l'affaire (quel que soit l'état)
            \App\Models\Cde::factory()->count(rand(1, 3))->create([
                'affaire_id' => $affaire->id,
            ]);
        });

        // Créer 15 réparations liées à des matériels et potentiellement à des affaires
        Reparation::factory()->count(15)->create([
            'affaire_id' => function () {
                return rand(0, 1) ? Affaire::inRandomOrder()->first()->id : null;
            },
            'materiel_id' => function () {
                return Materiel::inRandomOrder()->first()->id;
            }
        ])->each(function ($reparation) {
            // Créer une facture pour certaines réparations (ex: celles terminées)
            if ($reparation->status === 'terminee' || rand(0, 1)) {
                \App\Models\Facture::factory()->create([
                    'reparation_id' => $reparation->id,
                ]);
            }
        });
    }
}
