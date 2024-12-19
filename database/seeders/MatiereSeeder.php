<?php
namespace Database\Seeders;

use App\Models\Matiere;
use App\Models\Societe;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MatiereSeeder extends Seeder
{
    public function run()
    {
        // Créer une instance de Faker
        $faker = Faker::create();

        // Créer des sociétés
        $societes = Societe::factory(10)->create();  // Crée 10 sociétés

        // Créer des matières et lier les sociétés via la table intermédiaire
        Matiere::factory(100)->create()->each(function ($matiere) use ($societes, $faker) {
            $matiere->fournisseurs()->attach(
                $societes->random(3)->pluck('id'),  // Attribue 3 fournisseurs au hasard
                [
                    'ref_fournisseur' => $faker->word(),
                    'designation_fournisseur' => $faker->company(),
                    'prix' => $faker->randomFloat(2, 10, 200),
                    'date_dernier_prix' => now(),
                ]
            );
        });
    }
}
