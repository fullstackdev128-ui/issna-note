<?php

namespace App\Services;

use App\Models\Note;
use App\Models\UniteEnseignement;
use App\Models\ElementConstitutif;
use App\Models\Etudiant;

class NoteCalculatorService
{
    /**
     * Calcule la note finale d'un EC (CC 40% + SN 60% ou RP 100%)
     */
    public function calculerNoteFinaleEC(int $etudiantId, int $ecId, int $anneeId, int $semestre): ?float
    {
        $notes = Note::where([
            'etudiant_id' => $etudiantId,
            'element_constitutif_id' => $ecId,
            'annee_acad_id' => $anneeId,
            'semestre' => $semestre
        ])->get()->keyBy('type_examen');

        if ($notes->has('RP')) {
            return (float) $notes['RP']->valeur;
        }

        $cc = $notes->has('CC') ? (float)$notes['CC']->valeur : null;
        $sn = $notes->has('SN') ? (float)$notes['SN']->valeur : null;

        if ($cc !== null && $sn !== null) {
            return ($cc * 0.40) + ($sn * 0.60);
        }

        if ($sn !== null) return $sn;
        if ($cc !== null) return $cc;

        return null;
    }

    /**
     * Calcule la moyenne d'une UE
     */
    public function calculerMoyenneUE(int $etudiantId, int $ueId, int $anneeId, int $semestre): array
    {
        $ecs = ElementConstitutif::where('ue_id', $ueId)->get();
        
        $sommeNoteCredit = 0;
        $sommeCredits = 0;
        $detailEcs = [];
        $noteEliminatoire = false;

        foreach ($ecs as $ec) {
            $noteFinale = $this->calculerNoteFinaleEC($etudiantId, $ec->id, $anneeId, $semestre);
            
            if ($noteFinale === null) continue;
            
            $sommeNoteCredit += $noteFinale * $ec->credit;
            $sommeCredits += $ec->credit;
            
            if ($noteFinale < 8) $noteEliminatoire = true;
            
            $detailEcs[] = [
                'id'         => $ec->id,
                'nom'        => $ec->nom,
                'code_ec'    => $ec->code_ec,
                'credit'     => $ec->credit,
                'note'       => $noteFinale,
            ];
        }

        if ($sommeCredits === 0) {
            return ['moyenne' => null, 'validee' => false, 'detail_ecs' => [], 'credits_ue' => 0];
        }

        $moyenne = round($sommeNoteCredit / $sommeCredits, 2);
        $validee = $moyenne >= 10 && !$noteEliminatoire;

        return [
            'moyenne'    => $moyenne,
            'validee'    => $validee,
            'detail_ecs' => $detailEcs,
            'credits_ue' => $sommeCredits,
        ];
    }

    /**
     * Calcule le résultat semestriel complet
     */
    public function calculerResultatSemestre(int $etudiantId, int $anneeId, int $semestre): array
    {
        $etudiant = Etudiant::findOrFail($etudiantId);
        $ues = UniteEnseignement::where('specialite_id', $etudiant->specialite_id)
            ->where('semestre', $semestre)
            ->orderByRaw("CASE WHEN type_ue = 'Fondamentale' THEN 1 WHEN type_ue = 'Professionnelle' THEN 2 ELSE 3 END")
            ->orderBy('code_ue')
            ->get();

        $detail_ues = [];
        $sommeMoyennesUE = 0;
        $totalCreditsSemestre = 0;
        $creditsValides = 0;
        $nbUesCalculees = 0;
        $estPartielSemestre = false;

        foreach ($ues as $ue) {
            $resUE = $this->calculerMoyenneUE($etudiantId, $ue->id, $anneeId, $semestre);
            
            if ($resUE['moyenne'] === null) {
                $estPartielSemestre = true;
            } else {
                $sommeMoyennesUE += $resUE['moyenne'];
                $nbUesCalculees++;
                if ($resUE['validee']) {
                    $creditsValides += $resUE['credits_ue'];
                }
            }
            $totalCreditsSemestre += $resUE['credits_ue'];
            $detail_ues[] = array_merge($resUE, ['ue' => $ue]);
        }

        // La moyenne semestrielle est la moyenne arithmétique des moyennes d'UE
        $moyenneSem = $nbUesCalculees > 0 ? round($sommeMoyennesUE / $nbUesCalculees, 2) : 0;
        
        $mgpData = $this->getMGPetGrade($moyenneSem);

        return [
            'moyenne_sem' => $estPartielSemestre ? null : $moyenneSem,
            'credits_valides' => $creditsValides,
            'total_credits' => $totalCreditsSemestre,
            'mgp' => $mgpData['mgp'],
            'grade' => $mgpData['grade'],
            'mention' => $mgpData['mention'],
            'detail_ues' => $detail_ues,
            'est_partiel' => $estPartielSemestre
        ];
    }

    public function getMGPetGrade(float $moyenne): array
    {
        if ($moyenne >= 18) return ['grade' => 'A+', 'mgp' => 4.0, 'mention' => 'Excellent'];
        if ($moyenne >= 16) return ['grade' => 'A',  'mgp' => 3.7, 'mention' => 'Très Bien'];
        if ($moyenne >= 14) return ['grade' => 'B+', 'mgp' => 3.3, 'mention' => 'Bien'];
        if ($moyenne >= 12) return ['grade' => 'B',  'mgp' => 3.0, 'mention' => 'Bien'];
        if ($moyenne >= 11) return ['grade' => 'C+', 'mgp' => 2.3, 'mention' => 'Assez Bien'];
        if ($moyenne >= 10) return ['grade' => 'C',  'mgp' => 2.0, 'mention' => 'Passable'];
        if ($moyenne >= 8)  return ['grade' => 'D',  'mgp' => 1.7, 'mention' => 'Insuffisant'];
        if ($moyenne >= 6)  return ['grade' => 'E',  'mgp' => 1.0, 'mention' => 'Faible'];
        return ['grade' => 'F', 'mgp' => 0.0, 'mention' => 'Nul'];
    }
}
