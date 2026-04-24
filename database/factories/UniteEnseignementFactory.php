<?php

namespace Database\Factories;

use App\Models\Specialite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UniteEnseignement>
 */
class UniteEnseignementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'specialite_id' => Specialite::factory(),
            'code_ue' => strtoupper($this->faker->unique()->lexify('UE###')),
            'nom' => $this->faker->word(),
            'type_ue' => 'Fondamentale',
            'niveau' => 1,
            'semestre' => 1,
        ];
    }
}
