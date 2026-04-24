<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etudiant;
use App\Models\AnneeAcademique;
use App\Models\ElementConstitutif;
use App\Models\Note;
use App\Models\User;

class RealNotesSeeder extends Seeder
{
    public function run(): void
    {
        $etudiant = Etudiant::where('matricule', '24SI0001')->firstOrFail();
        $annee = AnneeAcademique::where('libelle', '2024-2025')->firstOrFail();
        $user = User::first(); // Super Admin
        $semestre = 1;

        // Notes à saisir (CC uniquement pour ces exemples)
        $notes_data = [
            // SIN111
            'SIN111-1' => 14, // AP1
            'SIN111-2' => 15, // AP2
            'SIN111-3' => 13, // AP3
            'SIN111-4' => 13, // Pharma
            
            // SIN121
            'SIN121-1' => 14, // Philo
            'SIN121-2' => 14, // Ethique
            'SIN121-3' => 12, // Secours
            'SIN121-4' => 18, // Techniques
            'SIN121-5' => 13, // Hygiène
            'SIN121-6' => 17, // Stage
            'SIN121-7' => 10, // Méthodes
            
            // SIN131
            'SIN131-1' => 15, // Français
            'SIN131-2' => 12, // Anglais
            'SIN131-3' => 16, // TIC
        ];

        foreach ($notes_data as $code_ec => $valeur) {
            $ec = ElementConstitutif::where('code_ec', $code_ec)->firstOrFail();
            
            Note::updateOrCreate(
                [
                    'etudiant_id' => $etudiant->id,
                    'element_constitutif_id' => $ec->id,
                    'annee_acad_id' => $annee->id,
                    'semestre' => $semestre,
                    'type_examen' => 'CC',
                ],
                [
                    'valeur' => $valeur,
                    'saisi_par' => $user->id,
                    'date_saisie' => now(),
                ]
            );
        }
    }
}
