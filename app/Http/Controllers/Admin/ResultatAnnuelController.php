<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\AnneeAcademique;
use App\Models\ResultatSemestre;
use App\Models\ResultatAnnuel;
use App\Services\ResultatAnnuelService;
use Illuminate\Support\Facades\Auth;

class ResultatAnnuelController extends Controller
{
    protected $service;

    public function __construct(ResultatAnnuelService $service)
    {
        $this->service = $service;
    }

    public function calculer()
    {
        $annees = AnneeAcademique::orderBy('date_debut', 'desc')->get();
        return view('resultats.annuels.calculer', compact('annees'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'annee_acad_id' => 'required|exists:annee_academiques,id',
        ]);

        $etudiant = Etudiant::with(['specialite.filiere', 'campus'])->findOrFail($request->etudiant_id);
        $annee = AnneeAcademique::findOrFail($request->annee_acad_id);
        
        // Détection automatique du niveau de l'étudiant
        $niveau = $etudiant->niveau_actuel;

        $resultat = $this->service->calculerResultatAnnuel($etudiant->id, $annee->id, $niveau);

        // Vérifier s'il existe déjà un résultat en base pour la vue preview
        $resultatEnBase = ResultatAnnuel::where([
            'etudiant_id' => $etudiant->id,
            'annee_acad_id' => $annee->id,
            'niveau' => $niveau
        ])->first();

        return view('resultats.annuels.preview', compact('etudiant', 'annee', 'niveau', 'resultat', 'resultatEnBase'));
    }

    public function valider(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'annee_acad_id' => 'required|exists:annee_academiques,id',
            'decision_jury' => 'required|in:Admis(e),Ajourné(e),Autorisé(e) à continuer,Exclu(e)',
        ]);

        $etudiant = Etudiant::findOrFail($request->etudiant_id);
        $niveau = $etudiant->niveau_actuel;

        // Calcul des semestres absolus basés sur le niveau de l'étudiant
        $semestresAttendus = [($niveau * 2) - 1, $niveau * 2];

        $unSemestreValide = ResultatSemestre::where([
            'etudiant_id'   => $request->etudiant_id,
            'annee_acad_id' => $request->annee_acad_id,
            'valide'        => true,
        ])->whereIn('semestre', $semestresAttendus)->exists();

        if (!$unSemestreValide) {
            return back()->with('error', 'Aucun résultat semestriel validé.');
        }

        $res = $this->service->calculerResultatAnnuel($request->etudiant_id, $request->annee_acad_id, $niveau);

        ResultatAnnuel::updateOrCreate(
            [
                'etudiant_id' => $request->etudiant_id,
                'annee_acad_id' => $request->annee_acad_id,
                'niveau' => $niveau,
            ],
            [
                'moyenne_s1' => $res['moyenne_s1'],
                'moyenne_s2' => $res['moyenne_s2'],
                'moyenne_annuelle' => $res['moyenne_annuelle'],
                'credits_valides_s1' => $res['credits_valides_s1'],
                'credits_valides_s2' => $res['credits_valides_s2'],
                'credits_valides_total' => $res['credits_valides_total'],
                'total_credits' => $res['total_credits'],
                'mgp_annuel' => $res['mgp_annuel'],
                'grade_annuel' => $res['grade_annuel'],
                'mention_annuelle' => $res['mention_annuelle'],
                'decision_jury' => $request->decision_jury,
                'valide_par' => Auth::id(),
                'date_calcul' => now(),
            ]
        );

        return redirect()->route('resultats.annuels.show', [
            'etudiantId' => $request->etudiant_id, 
            'anneeId' => $request->annee_acad_id, 
            'niveau' => $niveau
        ])->with('success', 'Résultat annuel validé avec succès.');
    }

    public function show(int $etudiantId, int $anneeId, int $niveau)
    {
        $etudiant = Etudiant::with(['specialite.filiere', 'campus'])->findOrFail($etudiantId);
        $annee = AnneeAcademique::findOrFail($anneeId);
        $resultatAnnuel = ResultatAnnuel::where([
            'etudiant_id' => $etudiantId,
            'annee_acad_id' => $anneeId,
            'niveau' => $niveau
        ])->firstOrFail();

        $resultatCalcul = $this->service->calculerResultatAnnuel($etudiantId, $anneeId, $niveau);

        return view('resultats.annuels.show', compact('etudiant', 'annee', 'niveau', 'resultatAnnuel', 'resultatCalcul'));
    }
}
