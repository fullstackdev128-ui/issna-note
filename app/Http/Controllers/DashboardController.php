<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Specialite;
use App\Models\Note;
use App\Models\AnneeAcademique;

class DashboardController extends Controller
{
    public function index()
    {
        $anneeActive = AnneeAcademique::where('active', true)->first();
        
        $stats = [
            'total_etudiants' => Etudiant::where('statut', 'actif')->count(),
            'total_specialites' => Specialite::where('actif', true)->count(),
            'notes_saisies' => $anneeActive ? Note::where('annee_acad_id', $anneeActive->id)->count() : 0,
            'annee_active_libelle' => $anneeActive ? $anneeActive->libelle : 'Aucune année active',
        ];

        return view('dashboard', compact('stats'));
    }
}
