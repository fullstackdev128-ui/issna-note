<?php

namespace App\Services;

use App\Models\ResultatSemestre;
use App\Models\ResultatAnnuel;
use App\Models\AnneeAcademique;
use App\Models\Etudiant;
use App\Services\NoteCalculatorService;

class ResultatAnnuelService
{
    protected $calculator;

    public function __construct(NoteCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    public function getLibelleComplet(Etudiant $etudiant, int $niveau): string
    {
        $specialite = $etudiant->specialite;
        $semA = ($niveau * 2) - 1;
        $semB = $niveau * 2;

        $typeDiplome = match($specialite->type_diplome) {
            'BTS'     => "BTS — Année {$niveau}",
            'Licence' => "Licence — Année {$niveau}",
            'Master1' => "Master 1 — Année {$niveau}",
            'Master2' => "Master 2 — Année {$niveau}",
            default   => "Année {$niveau}",
        };

        return "{$typeDiplome} (S{$semA} + S{$semB})";
    }

    public function getLibelleNiveau(Etudiant $etudiant, int $niveau): string
    {
        return $this->getLibelleComplet($etudiant, $niveau);
    }

    public function calculerResultatAnnuel(int $etudiantId, int $anneeId, int $niveau): array 
    { 
        $etudiant = Etudiant::with('specialite')->findOrFail($etudiantId);
        
        // Calcul des semestres absolus basés sur le niveau
        $semA = ($niveau * 2) - 1;
        $semB = ($niveau * 2);
    
        // Récupérer résultats semestriels (S_A et S_B)
        $resS1 = ResultatSemestre::where([ 
            'etudiant_id'   => $etudiantId, 
            'annee_acad_id' => $anneeId, 
            'semestre'      => $semA,
            'valide'        => true,
        ])->first(); 
    
        $resS2 = ResultatSemestre::where([ 
            'etudiant_id'   => $etudiantId, 
            'annee_acad_id' => $anneeId, 
            'semestre'      => $semB,
            'valide'        => true,
        ])->first(); 
    
        // Crédits par défaut
        $credS1 = $resS1?->total_credits ?? 30; 
        $credS2 = $resS2?->total_credits ?? 30; 
        $totalCredits = $credS1 + $credS2;
    
        $moyS1 = $resS1?->moyenne_sem ?? null; 
        $moyS2 = $resS2?->moyenne_sem ?? null; 
    
        // Moyenne annuelle pondérée par les crédits
        $moyAnnuelle = null; 
        if ($moyS1 !== null && $moyS2 !== null) { 
            $moyAnnuelle = round( 
                (($moyS1 * $credS1) + ($moyS2 * $credS2)) / $totalCredits, 
                2 
            ); 
        } elseif ($moyS1 !== null) { 
            $moyAnnuelle = $moyS1; 
        } elseif ($moyS2 !== null) { 
            $moyAnnuelle = $moyS2; 
        } 
    
        // MGP annuel pondéré par crédits valides uniquement
        $credValS1 = $resS1?->credits_valides ?? 0; 
        $credValS2 = $resS2?->credits_valides ?? 0; 
        $totalCredVal = $credValS1 + $credValS2; 
    
        $mgpAnnuel = null; 
        if ($totalCredVal > 0) { 
            $mgpS1 = $resS1?->mgp ?? 0; 
            $mgpS2 = $resS2?->mgp ?? 0; 
            $mgpAnnuel = round( 
                (($mgpS1 * $credValS1) + ($mgpS2 * $credValS2)) / $totalCredVal, 
                2 
            ); 
        } 
    
        // Grade/Mention basé sur moyenne annuelle
        $gradeInfo = $moyAnnuelle !== null 
            ? $this->calculator->getMGPetGrade($moyAnnuelle) 
            : ['grade' => null, 'mgp' => null, 'mention' => null]; 
    
        return [ 
            'niveau'                => $niveau,
            'libelle_niveau'        => $this->getLibelleNiveau($etudiant, $niveau),
            'resultat_s1'           => $resS1, 
            'resultat_s2'           => $resS2, 
            'moyenne_s1'            => $moyS1, 
            'moyenne_s2'            => $moyS2, 
            'moyenne_annuelle'      => $moyAnnuelle, 
            'credits_valides_s1'    => $credValS1, 
            'credits_valides_s2'    => $credValS2, 
            'credits_valides_total' => $totalCredVal, 
            'total_credits'         => $totalCredits, 
            'mgp_annuel'            => $mgpAnnuel, 
            'grade_annuel'          => $gradeInfo['grade'], 
            'mention_annuelle'      => $gradeInfo['mention'], 
            's1_valide'             => $resS1 !== null,
            's2_valide'             => $resS2 !== null,
            'semA_label'            => "S$semA",
            'semB_label'            => "S$semB",
        ]; 
    }
}
