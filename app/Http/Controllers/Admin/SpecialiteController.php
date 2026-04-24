<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialite;
use App\Models\Filiere;
use Illuminate\Http\Request;

class SpecialiteController extends Controller
{
    public function index(Request $request)
    {
        $filieres = Filiere::all();
        $query = Specialite::with('filiere')->withCount('uniteEnseignements');

        if ($request->filled('filiere_id')) {
            $query->where('filiere_id', $request->filiere_id);
        }

        $specialites = $query->get()->groupBy('filiere.nom');

        return view('referentiel.specialites.index', compact('specialites', 'filieres'));
    }

    public function create()
    {
        $filieres = Filiere::where('actif', true)->get();
        return view('referentiel.specialites.create', compact('filieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'code' => 'required|unique:specialites,code|max:10',
            'nom' => 'required|max:255',
            'duree_ans' => 'required|integer|min:1|max:10',
            'type_diplome' => 'required|in:BTS,Licence,Master1,Master2',
            'actif' => 'boolean'
        ]);

        Specialite::create($request->all());

        return redirect()->route('referentiel.specialites.index')->with('success', 'Spécialité créée.');
    }

    public function edit(Specialite $specialite)
    {
        $filieres = Filiere::where('actif', true)->get();
        return view('referentiel.specialites.edit', compact('specialite', 'filieres'));
    }

    public function update(Request $request, Specialite $specialite)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'code' => 'required|max:10|unique:specialites,code,' . $specialite->id,
            'nom' => 'required|max:255',
            'duree_ans' => 'required|integer|min:1|max:10',
            'type_diplome' => 'required|in:BTS,Licence,Master1,Master2',
            'actif' => 'boolean'
        ]);

        $specialite->update($request->all());

        return redirect()->route('referentiel.specialites.index')->with('success', 'Spécialité mise à jour.');
    }

    public function destroy(Specialite $specialite)
    {
        if ($specialite->uniteEnseignements()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une spécialité ayant des UE liées.');
        }

        $specialite->delete();
        return redirect()->route('referentiel.specialites.index')->with('success', 'Spécialité supprimée.');
    }
}
