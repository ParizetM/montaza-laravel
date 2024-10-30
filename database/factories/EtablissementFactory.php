<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Etablissement>
 */
class EtablissementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $societe = \App\Models\Societe::factory()->create();
        return [
            'nom' => $this->faker->company,
            'adresse' => $this->faker->address,
            'code_postal' => $this->faker->postcode,
            'ville' => $this->faker->city,
            'societe_id' => $societe,
            'siret' => $societe->siren . $this->faker->numberBetween(10000, 99999),
        ];
    }
}
