<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etudiant;
use App\Models\Specialite;
use App\Models\AnneeAcademique;
use App\Models\Campus;

class TestEtudiantSeeder extends Seeder
{
    public function run(): void
    {
        $si = Specialite::where('code', 'SI')->first();
        $annee = AnneeAcademique::where('libelle', '2024-2025')->first();
        $campus = Campus::first();

        $service = new \App\Services\MatriculeGeneratorService();
        $matricule = $service->generer($si, $annee);

        Etudiant::create([
            'matricule' => $matricule,
            'nom' => 'BIEGWEN KERE',
            'prenoms' => 'Doriane Jeanine',
            'date_naissance' => '2005-05-15',
            'genre' => 'F',
            'specialite_id' => $si->id,
            'campus_id' => $campus->id,
            'niveau_actuel' => 1,
            'annee_acad_id' => $annee->id,
            'date_inscription' => now(),
            'statut' => 'actif',
        ]);
    }
}
