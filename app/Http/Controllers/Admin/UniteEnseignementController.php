<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniteEnseignement;
use App\Models\Specialite;
use Illuminate\Http\Request;

class UniteEnseignementController extends Controller
{
    public function index(Request $request)
    {
        $specialites = Specialite::all();
        $query = UniteEnseignement::with('specialite')->withCount('elementConstitutifs');

        if ($request->filled('specialite_id')) {
            $query->where('specialite_id', $request->specialite_id);
        }

        if ($request->filled('semestre')) {
            $query->where('semestre', $request->semestre);
        }

        $ues = $query->orderBy('specialite_id')->orderBy('semestre')->get();

        return view('referentiel.ues.index', compact('ues', 'specialites'));
    }

    public function create()
    {
        $specialites = Specialite::where('actif', true)->get();
        return view('referentiel.ues.create', compact('specialites'));
    }

    public function store(Request $request)
    {
        $specialite = Specialite::findOrFail($request->specialite_id);
        $maxSemestre = $specialite->duree_ans * 2;

        $request->validate([
            'specialite_id' => 'required|exists:specialites,id',
            'code_ue' => 'required|max:20',
            'nom' => 'required|max:255',
            'type_ue' => 'required|in:Fondamentale,Professionnelle,Transversale',
            'niveau' => 'required|integer|min:1|max:5',
            'semestre' => 'required|integer|min:1|max:' . $maxSemestre
        ]);

        UniteEnseignement::create($request->all());

        return redirect()->route('referentiel.ues.index')->with('success', 'UE créée.');
    }

    public function edit(UniteEnseignement $ue)
    {
        $specialites = Specialite::where('actif', true)->get();
        return view('referentiel.ues.edit', compact('ue', 'specialites'));
    }

    public function update(Request $request, UniteEnseignement $ue)
    {
        $specialite = Specialite::findOrFail($request->specialite_id);
        $maxSemestre = $specialite->duree_ans * 2;

        $request->validate([
            'specialite_id' => 'required|exists:specialites,id',
            'code_ue' => 'required|max:20',
            'nom' => 'required|max:255',
            'type_ue' => 'required|in:Fondamentale,Professionnelle,Transversale',
            'niveau' => 'required|integer|min:1|max:5',
            'semestre' => 'required|integer|min:1|max:' . $maxSemestre
        ]);

        $ue->update($request->all());

        return redirect()->route('referentiel.ues.index')->with('success', 'UE mise à jour.');
    }

    public function destroy(UniteEnseignement $ue)
    {
        if ($ue->elementConstitutifs()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une UE ayant des EC liés.');
        }

        $ue->delete();
        return redirect()->route('referentiel.ues.index')->with('success', 'UE supprimée.');
    }
}
