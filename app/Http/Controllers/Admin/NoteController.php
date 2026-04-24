<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\AnneeAcademique;
use App\Models\UniteEnseignement;
use App\Models\ElementConstitutif;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    public function saisie(Request $request)
    {
        $anneeActive = AnneeAcademique::where('active', true)->firstOrFail();
        
        $etudiants = [];
        if ($request->filled('search')) {
            $search = $request->search;
            $etudiants = Etudiant::where('nom', 'LIKE', "%{$search}%")
                ->orWhere('prenoms', 'LIKE', "%{$search}%")
                ->orWhere('matricule', 'LIKE', "%{$search}%")
                ->get();
        }

        $etudiant = null;
        $ues = [];
        if ($request->filled('etudiant_id')) {
            $etudiant = Etudiant::with('specialite')->findOrFail($request->etudiant_id);
            if ($request->filled('semestre')) {
                $ues = UniteEnseignement::with('elementConstitutifs')
                    ->where('specialite_id', $etudiant->specialite_id)
                    ->where('semestre', $request->semestre)
                    ->get();
            }
        }

        return view('notes.saisie', compact('etudiants', 'etudiant', 'ues', 'anneeActive'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'annee_acad_id' => 'required|exists:annee_academiques,id',
            'semestre' => 'required|integer',
            'notes' => 'required|array',
            'notes.*.*' => 'nullable|numeric|min:0|max:20', // notes[ec_id][type]
        ]);

        $anneeActive = AnneeAcademique::where('active', true)->firstOrFail();
        
        DB::transaction(function () use ($request, $anneeActive) {
            foreach ($request->notes as $ec_id => $types) {
                foreach ($types as $type => $valeur) {
                    if ($valeur === null) continue;

                    // Règle RP : si type=RP, vérifie qu'une note SN existe déjà
                    if ($type === 'RP') {
                        $snExists = Note::where([
                            'etudiant_id' => $request->etudiant_id,
                            'element_constitutif_id' => $ec_id,
                            'annee_acad_id' => $request->annee_acad_id,
                            'semestre' => $request->semestre,
                            'type_examen' => 'SN'
                        ])->exists();

                        if (!$snExists) {
                            throw new \Exception("Impossible de saisir une note de Rattrapage (RP) sans note de Session Normale (SN) préalable pour l'élément constitutif ID: $ec_id.");
                        }
                    }

                    Note::updateOrCreate(
                        [
                            'etudiant_id' => $request->etudiant_id,
                            'element_constitutif_id' => $ec_id,
                            'annee_acad_id' => $request->annee_acad_id,
                            'semestre' => $request->semestre,
                            'type_examen' => $type,
                        ],
                        [
                            'valeur' => $valeur,
                            'saisi_par' => Auth::id(),
                            'date_saisie' => now(),
                        ]
                    );
                }
            }
        });

        return redirect()->back()->with('success', 'Notes enregistrées avec succès.');
    }

    public function parEtudiant(Etudiant $etudiant)
    {
        $etudiant->load(['specialite.filiere', 'campus', 'anneeAcademique']);
        
        $notes = Note::with('elementConstitutif.ue')
            ->where('etudiant_id', $etudiant->id)
            ->get()
            ->groupBy(['semestre', 'elementConstitutif.ue.id']);

        return view('notes.par-etudiant', compact('etudiant', 'notes'));
    }

    public function modifier(Request $request, Note $note)
    {
        $request->validate([
            'valeur' => 'required|numeric|min:0|max:20',
            'motif_modification' => 'required|string|min:5',
        ]);

        $note->update([
            'valeur' => $request->valeur,
            'motif_modification' => $request->motif_modification,
            'modifie_par' => Auth::id(),
            'date_modification' => now(),
        ]);

        return redirect()->back()->with('success', 'Note modifiée avec succès.');
    }
}
