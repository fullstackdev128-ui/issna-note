<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Specialite;
use App\Models\Campus;
use App\Models\AnneeAcademique;
use App\Models\Filiere;
use App\Http\Requests\StoreEtudiantRequest;
use App\Services\MatriculeGeneratorService;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    protected $matriculeService;

    public function __construct(MatriculeGeneratorService $matriculeService)
    {
        $this->matriculeService = $matriculeService;
    }

    public function index(Request $request)
    {
        $query = Etudiant::with(['specialite.filiere', 'campus']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenoms', 'LIKE', "%{$search}%")
                  ->orWhere('matricule', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('specialite_id')) {
            $query->where('specialite_id', $request->specialite_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $etudiants = $query->orderBy('nom')->paginate(15)->withQueryString();
        $specialites = Specialite::where('actif', true)->orderBy('nom')->get();

        return view('etudiants.index', compact('etudiants', 'specialites'));
    }

    public function create()
    {
        $specialites = Specialite::with('filiere')->where('actif', true)->get();
        $filieres = Filiere::where('actif', true)->get();
        $campus = Campus::all();
        $anneeActive = AnneeAcademique::where('active', true)->first();

        return view('etudiants.create', compact('specialites', 'filieres', 'campus', 'anneeActive'));
    }

    public function store(StoreEtudiantRequest $request)
    {
        $anneeActive = AnneeAcademique::where('active', true)->firstOrFail();
        $specialite = Specialite::findOrFail($request->specialite_id);

        $matricule = $this->matriculeService->generer($specialite, $anneeActive);

        $etudiant = Etudiant::create(array_merge($request->validated(), [
            'matricule' => $matricule,
            'annee_acad_id' => $anneeActive->id,
            'date_inscription' => now(),
            'statut' => 'actif',
        ]));

        return redirect()->route('etudiants.show', $etudiant)
            ->with('success', "Étudiant créé avec succès. Matricule : {$matricule}");
    }

    public function show(Etudiant $etudiant)
    {
        $etudiant->load(['specialite.filiere', 'campus', 'anneeAcademique']);
        return view('etudiants.show', compact('etudiant'));
    }

    public function edit(Etudiant $etudiant)
    {
        $specialites = Specialite::with('filiere')->where('actif', true)->get();
        $filieres = Filiere::where('actif', true)->get();
        $campus = Campus::all();
        
        return view('etudiants.edit', compact('etudiant', 'specialites', 'filieres', 'campus'));
    }

    public function update(StoreEtudiantRequest $request, Etudiant $etudiant)
    {
        $etudiant->update($request->validated());

        return redirect()->route('etudiants.show', $etudiant)
            ->with('success', "Informations de l'étudiant mises à jour.");
    }
}
