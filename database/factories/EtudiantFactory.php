<?php

namespace Database\Factories;

use App\Models\AnneeAcademique;
use App\Models\Campus;
use App\Models\Specialite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Etudiant>
 */
class EtudiantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'matricule' => $this->faker->unique()->numerify('MAT####'),
            'nom' => $this->faker->lastName(),
            'prenoms' => $this->faker->firstName(),
            'date_naissance' => $this->faker->date(),
            'lieu_naissance' => $this->faker->city(),
            'genre' => $this->faker->randomElement(['M', 'F']),
            'telephone' => $this->faker->phoneNumber(),
            'campus_id' => Campus::factory(),
            'specialite_id' => Specialite::factory(),
            'niveau_actuel' => 1,
            'annee_acad_id' => AnneeAcademique::factory(),
            'statut' => 'Inscrit',
        ];
    }
}
