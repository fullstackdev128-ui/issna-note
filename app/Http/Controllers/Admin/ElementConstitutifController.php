<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElementConstitutif;
use App\Models\UniteEnseignement;
use App\Models\Specialite;
use Illuminate\Http\Request;

class ElementConstitutifController extends Controller
{
    public function index(Request $request)
    {
        $specialites = Specialite::where('actif', true)->with('filiere')->get();
        $ues = collect();
        $ecs = collect();
        
        $specialiteId = $request->get('specialite_id');
        $ueId = $request->get('ue_id');
        
        if ($specialiteId) {
            $ues = UniteEnseignement::where('specialite_id', $specialiteId)
                ->orderBy('semestre')->orderBy('nom')->get();
        }
        if ($ueId) {
            $ecs = ElementConstitutif::where('ue_id', $ueId)
                ->orderBy('nom')->get();
        }
        
        return view('referentiel.ecs.index', compact('specialites', 'ues', 'ecs', 'specialiteId', 'ueId'));
    }

    public function create(Request $request)
    {
        $ues = UniteEnseignement::orderBy('nom')->get();
        $ueId = $request->get('ue_id');
        return view('referentiel.ecs.create', compact('ues', 'ueId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ue_id'             => 'required|exists:unite_enseignements,id',
            'nom'               => 'required|string|max:150',
            'credit'            => 'required|integer|min:1|max:10',
            'code_ec'           => 'nullable|string|max:20',
            'note_eliminatoire' => 'nullable|numeric|min:0|max:20',
        ]);
        ElementConstitutif::create($request->all());
        return redirect()->route('referentiel.ecs.index', ['ue_id' => $request->ue_id])
            ->with('success', 'Matière créée avec succès.');
    }

    public function edit(ElementConstitutif $ec)
    {
        $ues = UniteEnseignement::orderBy('nom')->get();
        return view('referentiel.ecs.edit', compact('ec', 'ues'));
    }

    public function update(Request $request, ElementConstitutif $ec)
    {
        $request->validate([
            'ue_id'             => 'required|exists:unite_enseignements,id',
            'nom'               => 'required|string|max:150',
            'credit'            => 'required|integer|min:1|max:10',
            'code_ec'           => 'nullable|string|max:20',
            'note_eliminatoire' => 'nullable|numeric|min:0|max:20',
        ]);
        $ec->update($request->all());
        return redirect()->route('referentiel.ecs.index', ['ue_id' => $ec->ue_id])
            ->with('success', 'Matière mise à jour.');
    }

    public function destroy(ElementConstitutif $ec)
    {
        if ($ec->notes()->count() > 0) {
            return back()->with('error', 'Impossible : des notes sont liées à cette matière.');
        }
        $ec->delete();
        return back()->with('success', 'Matière supprimée.');
    }
}
