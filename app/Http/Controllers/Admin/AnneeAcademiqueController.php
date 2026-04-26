<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use Illuminate\Http\Request;

class AnneeAcademiqueController extends Controller
{
    public function index()
    {
        try {
            $annees = AnneeAcademique::orderBy('date_debut', 'desc')->get();
            return view('referentiel.annees.index', compact('annees'));
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }

    public function create()
    {
        return view('referentiel.annees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle'    => 'required|string|max:20|unique:annee_academiques',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after:date_debut',
        ]);
        AnneeAcademique::create(array_merge($request->all(), ['active' => false]));
        return redirect()->route('referentiel.annees.index')->with('success', 'Année créée.');
    }

    public function edit(AnneeAcademique $annee)
    {
        return view('referentiel.annees.edit', compact('annee'));
    }

    public function update(Request $request, AnneeAcademique $annee)
    {
        $request->validate([
            'libelle'    => 'required|string|max:20|unique:annee_academiques,libelle,'.$annee->id,
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after:date_debut',
        ]);
        $annee->update($request->only(['libelle', 'date_debut', 'date_fin']));
        return redirect()->route('referentiel.annees.index')->with('success', 'Année mise à jour.');
    }

    public function activer(AnneeAcademique $annee)
    {
        // Une seule année active à la fois
        AnneeAcademique::query()->update(['active' => false]);
        $annee->update(['active' => true]);
        return back()->with('success', "Année {$annee->libelle} activée.");
    }

    public function destroy(AnneeAcademique $annee)
    {
        if ($annee->notes()->count() > 0 || $annee->etudiants()->count() > 0) {
            return back()->with('error', 'Impossible : des données sont liées à cette année.');
        }
        $annee->delete();
        return back()->with('success', 'Année supprimée.');
    }
}
