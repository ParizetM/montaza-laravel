<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocieteContact>
 */
class SocieteContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'telephone_fixe' => '02' . $this->faker->numerify('########'),
            'telephone_portable' => '06' . $this->faker->numerify('########'),
            'etablissement_id' => \App\Models\Etablissement::factory(),
        ];
    }
}
