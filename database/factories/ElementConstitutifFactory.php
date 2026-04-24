<?php

namespace Database\Factories;

use App\Models\UniteEnseignement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ElementConstitutif>
 */
class ElementConstitutifFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ue_id' => UniteEnseignement::factory(),
            'nom' => $this->faker->word(),
            'code_ec' => strtoupper($this->faker->unique()->lexify('EC###')),
            'credit' => 3,
        ];
    }
}
