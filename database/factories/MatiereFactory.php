<?php

namespace Database\Factories;

use App\Models\Matiere;
use App\Models\Societe;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatiereFactory extends Factory
{
    protected $model = Matiere::class;

    public function definition()
    {
        return [
            'ref_interne' => $this->faker->unique()->word(),
            'designation' => $this->faker->word(),
            'societe_id' => Societe::factory(),  // Associe une société pour le test
            'unite_id' => rand(1, 10),  // Assurez-vous de changer cela en fonction de vos données
            'sous_famille_id' => rand(1, 10),  // Assurez-vous de changer cela
            'dn' => rand(1, 100),
            'epaisseur' => $this->faker->randomFloat(2, 0.1, 10),
            'prix_moyen' => $this->faker->randomFloat(2, 1, 100),
            'date_dernier_achat' => $this->faker->date(),
            'quantite' => rand(1, 1000),
            'stock_min' => rand(1, 100),
        ];
    }
}
