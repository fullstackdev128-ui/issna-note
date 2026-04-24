<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiere;
use App\Models\Specialite;
use App\Models\AnneeAcademique;
use App\Models\UniteEnseignement;
use App\Models\ElementConstitutif;
use App\Models\Etudiant;
use App\Models\Campus;

class TestDataIDES1Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Campus
        $campus = Campus::firstOrCreate(['nom' => 'Douala'], ['code' => 'DLA', 'actif' => true]);

        // 2. Année Académique
        $annee = AnneeAcademique::firstOrCreate(
            ['libelle' => '2025-2026'],
            [
                'date_debut' => '2025-09-01',
                'date_fin' => '2026-07-31',
                'active' => true
            ]
        );
        // S'assurer qu'elle est active
        AnneeAcademique::query()->update(['active' => false]);
        $annee->update(['active' => true]);

        // 3. Filière
        $filiere = Filiere::firstOrCreate(
            ['code' => 'SI'],
            ['nom' => 'Soins Infirmiers', 'actif' => true]
        );

        // 4. Spécialité
        $specialite = Specialite::firstOrCreate(
            ['code' => 'IDE'],
            [
                'filiere_id' => $filiere->id,
                'nom' => 'Infirmier Diplômé d\'État',
                'duree_ans' => 3,
                'actif' => true
            ]
        );

        // 5. Étudiant (DONGMO RAISSA)
        Etudiant::updateOrCreate(
            ['matricule' => '26ID0001'],
            [
                'nom' => 'DONGMO',
                'prenoms' => 'RAISSA',
                'date_naissance' => '2005-05-15',
                'lieu_naissance' => 'Bafoussam',
                'genre' => 'F',
                'campus_id' => $campus->id,
                'specialite_id' => $specialite->id,
                'annee_acad_id' => $annee->id,
                'niveau_actuel' => 1,
                'statut' => 'actif'
            ]
        );

        // 6. UEs et ECs pour S1
        
        // UE 1 : Sciences Biologiques
        $ueBio = UniteEnseignement::updateOrCreate(
            ['code_ue' => 'BIO11', 'specialite_id' => $specialite->id, 'semestre' => 1],
            ['nom' => 'Sciences Biologiques', 'type_ue' => 'Fondamentale', 'niveau' => 1]
        );
        
        ElementConstitutif::updateOrCreate(
            ['code_ec' => 'BIO111', 'ue_id' => $ueBio->id],
            ['nom' => 'Anatomie et Physiologie', 'credit' => 3, 'note_eliminatoire' => 8]
        );
        ElementConstitutif::updateOrCreate(
            ['code_ec' => 'BIO112', 'ue_id' => $ueBio->id],
            ['nom' => 'Microbiologie et Immunologie', 'credit' => 2, 'note_eliminatoire' => 8]
        );

        // UE 2 : Sciences Infirmières
        $ueSin = UniteEnseignement::updateOrCreate(
            ['code_ue' => 'SIN11', 'specialite_id' => $specialite->id, 'semestre' => 1],
            ['nom' => 'Sciences Infirmières', 'type_ue' => 'Professionnelle', 'niveau' => 1]
        );
        
        ElementConstitutif::updateOrCreate(
            ['code_ec' => 'SIN111', 'ue_id' => $ueSin->id],
            ['nom' => 'Fondements des Soins Infirmiers', 'credit' => 4, 'note_eliminatoire' => 8]
        );
        ElementConstitutif::updateOrCreate(
            ['code_ec' => 'SIN112', 'ue_id' => $ueSin->id],
            ['nom' => 'Hygiène et Aseptie', 'credit' => 2, 'note_eliminatoire' => 8]
        );

        // UE 3 : Sciences Humaines
        $ueShu = UniteEnseignement::updateOrCreate(
            ['code_ue' => 'SHU11', 'specialite_id' => $specialite->id, 'semestre' => 1],
            ['nom' => 'Sciences Humaines', 'type_ue' => 'Transversale', 'niveau' => 1]
        );
        
        ElementConstitutif::updateOrCreate(
            ['code_ec' => 'SHU111', 'ue_id' => $ueShu->id],
            ['nom' => 'Psychologie', 'credit' => 2, 'note_eliminatoire' => 7]
        );
        ElementConstitutif::updateOrCreate(
            ['code_ec' => 'SHU112', 'ue_id' => $ueShu->id],
            ['nom' => 'Sociologie et Anthropologie', 'credit' => 2, 'note_eliminatoire' => 7]
        );
    }
}
