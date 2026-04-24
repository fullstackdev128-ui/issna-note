<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    public function index()
    {
        $filieres = Filiere::withCount('specialites')->get();
        return view('referentiel.filieres.index', compact('filieres'));
    }

    public function create()
    {
        return view('referentiel.filieres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:filieres,code|max:10',
            'nom' => 'required|max:255',
            'actif' => 'boolean'
        ]);

        Filiere::create($request->all());

        return redirect()->route('referentiel.filieres.index')->with('success', 'Filière créée avec succès.');
    }

    public function edit(Filiere $filiere)
    {
        return view('referentiel.filieres.edit', compact('filiere'));
    }

    public function update(Request $request, Filiere $filiere)
    {
        $request->validate([
            'code' => 'required|max:10|unique:filieres,code,' . $filiere->id,
            'nom' => 'required|max:255',
            'actif' => 'boolean'
        ]);

        $filiere->update($request->all());

        return redirect()->route('referentiel.filieres.index')->with('success', 'Filière mise à jour.');
    }

    public function destroy(Filiere $filiere)
    {
        if ($filiere->specialites()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une filière ayant des spécialités liées.');
        }

        $filiere->delete();
        return redirect()->route('referentiel.filieres.index')->with('success', 'Filière supprimée.');
    }
}
