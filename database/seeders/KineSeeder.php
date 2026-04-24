<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialite;
use App\Models\UniteEnseignement;
use App\Models\ElementConstitutif;

class KineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mise à jour de la spécialité KINE
        $kine = Specialite::where('code', 'KINE')->firstOrFail();
        $kine->update([
            'duree_ans' => 3,
            'diplome' => 'BTS'
        ]);

        $data = [
            // NIVEAU 1 · SEMESTRE 1
            [
                'niveau' => 1, 'semestre' => 1,
                'ues' => [
                    ['code' => 'KIN111', 'nom' => 'Biologie cellulaire - histologie - Anatomie Physiologie I', 'cr' => 7, 'type' => 'Fondamentale'],
                    ['code' => 'KIN112', 'nom' => 'Neurophysiologie - Kinésiologie - Chimie générale', 'cr' => 2, 'type' => 'Fondamentale'],
                    ['code' => 'KIN113', 'nom' => 'Psychologie - Sociologie - Anthropologie - Histoire Kiné', 'cr' => 4, 'type' => 'Transversale'],
                    ['code' => 'KIN114', 'nom' => 'Méthodologie générale de la Kinésithérapie et réadaptation I', 'cr' => 5, 'type' => 'Professionnelle'],
                    ['code' => 'KIN115', 'nom' => 'Maladies infectieuses et parasitaires', 'cr' => 5, 'type' => 'Professionnelle'],
                    ['code' => 'KIN116', 'nom' => 'Activités motrices - Éthique et déontologie professionnelle', 'cr' => 4, 'type' => 'Professionnelle'],
                    ['code' => 'KIN117', 'nom' => 'Français médical - Anglais médical - NTIC I', 'cr' => 3, 'type' => 'Transversale'],
                ]
            ],
            // NIVEAU 1 · SEMESTRE 2
            [
                'niveau' => 1, 'semestre' => 2,
                'ues' => [
                    ['code' => 'KIN121', 'nom' => 'Biomécanique - Anatomie physiologie II', 'cr' => 6, 'type' => 'Fondamentale'],
                    ['code' => 'KIN122', 'nom' => 'Pharmacologie générale et clinique', 'cr' => 3, 'type' => 'Fondamentale'],
                    ['code' => 'KIN123', 'nom' => 'Stage clinique 1', 'cr' => 6, 'type' => 'Professionnelle'],
                    ['code' => 'KIN124', 'nom' => 'Épidémiologie - Bio statistiques - Santé et développement', 'cr' => 7, 'type' => 'Fondamentale'],
                    ['code' => 'KIN125', 'nom' => 'Soins infirmiers et secourisme', 'cr' => 3, 'type' => 'Professionnelle'],
                    ['code' => 'KIN126', 'nom' => 'Droit lié à la profession', 'cr' => 2, 'type' => 'Transversale'],
                    ['code' => 'KIN127', 'nom' => 'Éducation civique et éthique', 'cr' => 3, 'type' => 'Transversale'],
                ]
            ],
            // NIVEAU 2 · SEMESTRE 3
            [
                'niveau' => 2, 'semestre' => 3,
                'ues' => [
                    ['code' => 'KIN231', 'nom' => 'Anatomie Physiologie III - Chimie physiologie', 'cr' => 6, 'type' => 'Fondamentale'],
                    ['code' => 'KIN232', 'nom' => 'Chimie minérale - Chimie organique', 'cr' => 3, 'type' => 'Fondamentale'],
                    ['code' => 'KIN233', 'nom' => 'Stage clinique 2', 'cr' => 6, 'type' => 'Professionnelle'],
                    ['code' => 'KIN234', 'nom' => 'Méthodologie de kinésithérapie et réadaptation II', 'cr' => 6, 'type' => 'Professionnelle'],
                    ['code' => 'KIN235', 'nom' => 'Psychologie appliquée à la kinésithérapie', 'cr' => 2, 'type' => 'Transversale'],
                    ['code' => 'KIN236', 'nom' => 'Pathologies spéciales et kinésithérapie spécifique I - IEC 2', 'cr' => 4, 'type' => 'Professionnelle'],
                    ['code' => 'KIN237', 'nom' => 'Français - Anglais médical - TIC', 'cr' => 3, 'type' => 'Transversale'],
                ]
            ],
            // NIVEAU 2 · SEMESTRE 4
            [
                'niveau' => 2, 'semestre' => 4,
                'ues' => [
                    ['code' => 'KIN241', 'nom' => 'Économie générale - Gestion hospitalière - Initiation recherche', 'cr' => 6, 'type' => 'Transversale'],
                    ['code' => 'KIN242', 'nom' => 'Programmes prioritaires de santé', 'cr' => 3, 'type' => 'Professionnelle'],
                    ['code' => 'KIN243', 'nom' => 'Méthodologie spéciale de kinésithérapie I', 'cr' => 3, 'type' => 'Professionnelle'],
                    ['code' => 'KIN244', 'nom' => 'Pathologies spéciales et kinésithérapie spécifiques II', 'cr' => 5, 'type' => 'Professionnelle'],
                    ['code' => 'KIN245', 'nom' => 'Stage communautaire 1', 'cr' => 6, 'type' => 'Professionnelle'],
                    ['code' => 'KIN246', 'nom' => 'Pharmacologie spéciale de kiné II', 'cr' => 4, 'type' => 'Fondamentale'],
                    ['code' => 'KIN247', 'nom' => 'Droit médical', 'cr' => 3, 'type' => 'Transversale'],
                ]
            ],
            // NIVEAU 3 · SEMESTRE 5
            [
                'niveau' => 3, 'semestre' => 5,
                'ues' => [
                    ['code' => 'KIN351', 'nom' => 'Neurophysiologie - Métabolique humaine - Économie de la santé', 'cr' => 6, 'type' => 'Fondamentale'],
                    ['code' => 'KIN352', 'nom' => 'Neurophysiologie humaine et physiologie des mouvements', 'cr' => 3, 'type' => 'Fondamentale'],
                    ['code' => 'KIN353', 'nom' => 'Méthodologie spéciale de kinésithérapie', 'cr' => 3, 'type' => 'Professionnelle'],
                    ['code' => 'KIN354', 'nom' => 'Pathologies spéciales gynécologiques', 'cr' => 3, 'type' => 'Professionnelle'],
                    ['code' => 'KIN355', 'nom' => 'Stage communautaire 2', 'cr' => 6, 'type' => 'Professionnelle'],
                    ['code' => 'KIN356', 'nom' => 'Stage clinique 3', 'cr' => 6, 'type' => 'Professionnelle'],
                    ['code' => 'KIN357', 'nom' => 'Français - Anglais - TIC 3', 'cr' => 3, 'type' => 'Transversale'],
                ]
            ],
            // NIVEAU 3 · SEMESTRE 6
            [
                'niveau' => 3, 'semestre' => 6,
                'ues' => [
                    ['code' => 'KIN361', 'nom' => 'Projet professionnel', 'cr' => 4, 'type' => 'Transversale'],
                    ['code' => 'KIN362', 'nom' => 'IEC 3 - Promotion de la santé', 'cr' => 5, 'type' => 'Professionnelle'],
                    ['code' => 'KIN363', 'nom' => 'Stage clinique 4', 'cr' => 6, 'type' => 'Professionnelle'],
                    ['code' => 'KIN364', 'nom' => 'Mise en situation professionnelle - pathologies spéciales I', 'cr' => 4, 'type' => 'Professionnelle'],
                    ['code' => 'KIN365', 'nom' => 'Mise en situation professionnelle - pathologies spéciales II', 'cr' => 4, 'type' => 'Professionnelle'],
                    ['code' => 'KIN366', 'nom' => 'Appareillage et prothèse en kinésithérapie', 'cr' => 4, 'type' => 'Professionnelle'],
                    ['code' => 'KIN367', 'nom' => 'Initiation à la recherche - Français - Anglais', 'cr' => 3, 'type' => 'Transversale'],
                ]
            ],
        ];

        foreach ($data as $semestreData) {
            foreach ($semestreData['ues'] as $ueItem) {
                // Créer ou mettre à jour l'UE avec le bon semestre absolu
                $ue = UniteEnseignement::updateOrCreate(
                    ['code_ue' => $ueItem['code']],
                    [
                        'specialite_id' => $kine->id,
                        'nom'           => $ueItem['nom'],
                        'type_ue'       => $ueItem['type'],
                        'niveau'        => $semestreData['niveau'],
                        'semestre'      => $semestreData['semestre'],
                    ]
                );

                // Créer ou mettre à jour l'EC
                ElementConstitutif::updateOrCreate(
                    ['code_ec' => $ueItem['code'] . '-1'],
                    [
                        'ue_id'             => $ue->id,
                        'nom'               => $ueItem['nom'],
                        'credit'            => $ueItem['cr'],
                        'note_eliminatoire' => 8.00,
                    ]
                );
            }
        }
    }
}
