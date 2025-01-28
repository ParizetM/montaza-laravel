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
        $random_cde_id = Cde::all()->random()->id;
        $random_societe_matiere_id = DB::table('societe_matiere')->inRandomOrder()->first()->id;
        $random_matiere_id = Matiere::all()->random()->id;
        return [
            'cde_id' => $random_cde_id,
            'poste' => $this->faker->numberBetween(1, 100),
            'matiere_id' => $random_matiere_id,
            'ref_interne' => $this->faker->optional()->word(),
            'ref_fournisseur' => $this->faker->optional()->word(),
            'designation' => $this->faker->sentence(),
            'quantite' => $this->faker->numberBetween(1, 1000),
            'date_livraison' => $this->faker->optional()->date(),
        ];
    }
}
