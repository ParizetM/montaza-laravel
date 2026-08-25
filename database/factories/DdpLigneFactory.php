<?php

namespace Database\Factories;

use App\Models\Ddp;
use App\Models\Matiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DdpLigne>
 */
class DdpLigneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ddps = Ddp::all();
        $matieres = Matiere::all();

        return [
            'ddp_id'      => $ddps->isNotEmpty() ? $ddps->random()->id : Ddp::factory(),
            'matiere_id'  => $matieres->isNotEmpty() ? $matieres->random()->id : null,
            'quantite'    => $this->faker->numberBetween(1, 100),
        ];
    }
}
