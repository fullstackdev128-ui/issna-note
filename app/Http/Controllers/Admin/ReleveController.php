<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\AnneeAcademique;
use App\Models\ResultatSemestre;
use App\Services\NoteCalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;

class ReleveController extends Controller
{
    protected $calculator;

    public function __construct(NoteCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    public function index()
    {
        $etudiants = Etudiant::all();
        $annees = AnneeAcademique::orderBy('libelle', 'desc')->get();
        return view('releves.index', compact('etudiants', 'annees'));
    }

    public function generer(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'annee_acad_id' => 'required|exists:annee_academiques,id',
            'semestres' => 'required|array',
        ]);

        $etudiant = Etudiant::with(['specialite.filiere', 'campus'])->findOrFail($request->etudiant_id);
        $annee = AnneeAcademique::findOrFail($request->annee_acad_id);
        
        $data_semestres = [];
        foreach ($request->semestres as $semestre) {
            $resultat = $this->calculator->calculerResultatSemestre($etudiant->id, $annee->id, $semestre);
            $resEnBase = ResultatSemestre::where([
                'etudiant_id' => $etudiant->id,
                'annee_acad_id' => $annee->id,
                'semestre' => $semestre
            ])->first();

            $data_semestres[] = [
                'numero' => $semestre,
                'resultat' => $resultat,
                'en_base' => $resEnBase
            ];
        }

        // Détection Relevé Annuel
        $semestresSelectionnes = array_map('intval', $request->semestres);
        sort($semestresSelectionnes);
        
        $estAnnuel = count($semestresSelectionnes) === 2 
            && ($semestresSelectionnes[1] - $semestresSelectionnes[0]) === 1 
            && $semestresSelectionnes[0] % 2 === 1;

        $resultatAnnuel = null;
        if ($estAnnuel) {
            $niveau = (int) ceil($semestresSelectionnes[0] / 2);
            
            $resultatAnnuel = \App\Models\ResultatAnnuel::where([
                'etudiant_id'   => $etudiant->id,
                'annee_acad_id' => $annee->id,
                'niveau'        => $niveau,
            ])->first();

            if (!$resultatAnnuel) {
                $resS1 = \App\Models\ResultatSemestre::where([
                    'etudiant_id'   => $etudiant->id,
                    'annee_acad_id' => $annee->id,
                    'semestre'      => $semestresSelectionnes[0],
                ])->first();

                $resS2 = \App\Models\ResultatSemestre::where([
                    'etudiant_id'   => $etudiant->id,
                    'annee_acad_id' => $annee->id,
                    'semestre'      => $semestresSelectionnes[1],
                ])->first();

                if ($resS1 && $resS2) {
                    $moyAnnuelle = round(($resS1->moyenne_sem + $resS2->moyenne_sem) / 2, 2);
                    $mgpAnnuel = round(($resS1->mgp + $resS2->mgp) / 2, 2);
                    $creditsTotal = $resS1->credits_valides + $resS2->credits_valides;
                    $gradeInfo = $this->calculator->getMGPetGrade($moyAnnuelle);

                    $resultatAnnuel = (object) [
                        'moyenne_annuelle'      => $moyAnnuelle,
                        'mgp_annuel'            => $mgpAnnuel,
                        'grade_annuel'          => $gradeInfo['grade'],
                        'mention_annuelle'       => $gradeInfo['mention'],
                        'credits_valides_total'  => $creditsTotal,
                        'decision_jury'          => null,
                    ];
                }
            }
        }

        $data = [
            'etudiant' => $etudiant,
            'annee' => $annee->libelle,
            'semestres' => $data_semestres,
            'date' => now()->format('d/m/Y'),
            'resultat_annuel' => $resultatAnnuel,
            'est_annuel' => $estAnnuel,
        ];

        $pdf = Pdf::loadView('releves.pdf', $data);
        return $pdf->stream('releve_'.$etudiant->matricule.'.pdf');
    }
}
