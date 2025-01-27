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
        $societes = Societe::all();
        // Créer des matières et lier les sociétés via la table intermédiaire
        Matiere::factory(100)->create()->each(function ($matiere) use ($societes, $faker) {
            $matiere->fournisseurs()->attach(
                $societes->whereIn('societe_type_id', [2, 3])->random(rand(1, 10))->pluck('id'),
                [
                    'ref_fournisseur' => strtoupper($faker->lexify('??')) . '-' . $faker->numerify('####'),
                    'designation_fournisseur' => $faker->word(),
                    'prix' => $faker->randomFloat(2, 10, 200),
                    'unite_id' => $faker->randomElement([1, 2, 3, 4, 6, 19]),
                    'date_dernier_prix' => now(),
                ]
            );
        });
    }
}
