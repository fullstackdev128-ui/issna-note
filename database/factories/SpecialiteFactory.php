<?php

namespace Database\Factories;

use App\Models\Filiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialite>
 */
class SpecialiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'filiere_id' => Filiere::factory(),
            'nom' => $this->faker->word(),
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'duree_ans' => 3,
            'type_diplome' => 'BTS',
            'actif' => true,
        ];
    }
}
