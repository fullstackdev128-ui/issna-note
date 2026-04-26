<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\AnneeAcademique;
use App\Models\ResultatSemestre;
use App\Services\NoteCalculatorService;
use Illuminate\Support\Facades\DB;

class ResultatSemestreController extends Controller
{
    protected $calculator;

    public function __construct(NoteCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    public function calculer()
    {
        $annees = AnneeAcademique::orderBy('date_debut', 'desc')->get();
        return view('resultats.calculer', compact('annees'));
    }

    public function preview(Request $request)
    {
        $etudiant = Etudiant::with('specialite')->findOrFail($request->etudiant_id);
        $maxSemestre = $etudiant->specialite->duree_ans * 2;

        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'annee_acad_id' => 'required|exists:annee_academiques,id',
            'semestre' => 'required|integer|min:1|max:' . $maxSemestre,
        ]);

        $annee = AnneeAcademique::findOrFail($request->annee_acad_id);
        $semestre = $request->semestre;

        $resultat = $this->calculator->calculerResultatSemestre($etudiant->id, $annee->id, $semestre);

        return view('resultats.preview', compact('etudiant', 'annee', 'semestre', 'resultat'));
    }

    public function valider(Request $request)
    {
        try {
            $request->validate([
                'etudiant_id' => 'required|exists:etudiants,id',
                'annee_acad_id' => 'required|exists:annee_academiques,id',
                'semestre' => 'required|integer',
                'decision_jury' => 'required|in:Admis(e),Ajourné(e),Autorisé(e) à continuer,Exclu(e)',
            ]);

            $resultatData = $this->calculator->calculerResultatSemestre($request->etudiant_id, $request->annee_acad_id, $request->semestre);

            if ($resultatData['est_partiel']) {
                return back()->with('error', 'Impossible de valider un résultat partiel. Toutes les notes doivent être saisies.');
            }

            ResultatSemestre::updateOrCreate(
                [
                    'etudiant_id' => $request->etudiant_id,
                    'annee_acad_id' => $request->annee_acad_id,
                    'semestre' => $request->semestre,
                ],
                [
                    'total_credits' => $resultatData['total_credits'],
                    'credits_valides' => $resultatData['credits_valides'],
                    'moyenne_sem' => $resultatData['moyenne_sem'],
                    'mgp' => $resultatData['mgp'],
                    'grade' => $resultatData['grade'],
                    'mention' => $resultatData['mention'],
                    'decision_jury' => $request->decision_jury,
                    'date_calcul' => now(),
                    'valide' => true,
                ]
            );

            return redirect()->route('resultats.show', [
                'etudiantId' => $request->etudiant_id,
                'anneeId' => $request->annee_acad_id,
                'semestre' => $request->semestre
            ])->with('success', 'Résultat semestriel validé et enregistré.');
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }

    public function show(int $etudiantId, int $anneeId, int $semestre)
    {
        $etudiant = Etudiant::with(['specialite.filiere', 'campus'])->findOrFail($etudiantId);
        $annee = AnneeAcademique::findOrFail($anneeId);
        $resultatEnBase = ResultatSemestre::where([
            'etudiant_id' => $etudiantId,
            'annee_acad_id' => $anneeId,
            'semestre' => $semestre
        ])->firstOrFail();

        $resultatCalcul = $this->calculator->calculerResultatSemestre($etudiantId, $anneeId, $semestre);

        return view('resultats.show', compact('etudiant', 'annee', 'semestre', 'resultatEnBase', 'resultatCalcul'));
    }
}
