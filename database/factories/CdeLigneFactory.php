<?php

namespace Database\Factories;

use App\Models\Cde;
use App\Models\Matiere;
use DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CdeLigne>
 */
class CdeLigneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cdes     = Cde::all();
        $matieres = Matiere::all();

        return [
            'cde_id'              => $cdes->isNotEmpty() ? $cdes->random()->id : Cde::factory(),
            'poste'               => $this->faker->numberBetween(1, 100),
            'matiere_id'          => $matieres->isNotEmpty() ? $matieres->random()->id : null,
            'ref_interne'         => $this->faker->optional()->word(),
            'ref_fournisseur'     => $this->faker->optional()->word(),
            'designation'         => $this->faker->sentence(),
            'prix_unitaire'       => $this->faker->randomFloat(2, 0.01, 1000),
            'quantite'            => $this->faker->numberBetween(1, 1000),
            'date_livraison'      => $this->faker->optional()->date(),
            'date_livraison_reelle' => null,
            'is_stocke'           => false,
            'non_livre'           => false,
            'sous_ligne'          => false,
        ];
    }
}
