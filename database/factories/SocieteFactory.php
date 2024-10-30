<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Societe>
 */
class SocieteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'raison_sociale' => $this->faker->company,
            'siren' => $this->faker->numberBetween(100000000, 999999999),
            'forme_juridique_id' => \App\Models\FormeJuridique::inRandomOrder()->first()->id,
            'code_ape_id' => \App\Models\CodeApe::inRandomOrder()->first()->id,
            'societe_type_id' => \App\Models\SocieteType::inRandomOrder()->first()->id,
            'site_web' => $this->faker->url,
        ];
    }
}
