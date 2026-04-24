<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\EtudiantController;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Admin\ResultatSemestreController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des Étudiants
    Route::resource('etudiants', EtudiantController::class)->except(['destroy']);

    // Gestion des Notes
    Route::get('/notes/saisie', [NoteController::class, 'saisie'])->name('notes.saisie');
    Route::post('/notes/saisie', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/etudiant/{etudiant}', [NoteController::class, 'parEtudiant'])->name('notes.etudiant');
    Route::post('/notes/{note}/modifier', [NoteController::class, 'modifier'])->name('notes.modifier')->middleware('role:super_admin');

    // Gestion des Résultats
    Route::get('/resultats/calculer', [ResultatSemestreController::class, 'calculer'])->name('resultats.calculer');
    Route::post('/resultats/calculer', [ResultatSemestreController::class, 'preview'])->name('resultats.preview');
    Route::post('/resultats/valider', [ResultatSemestreController::class, 'valider'])->name('resultats.valider');
    Route::get('/resultats/{etudiantId}/{anneeId}/{semestre}', [ResultatSemestreController::class, 'show'])->name('resultats.show');

    // Résultats annuels
    Route::get('/resultats-annuels/calculer', [App\Http\Controllers\Admin\ResultatAnnuelController::class, 'calculer'])->name('resultats.annuels.calculer');
    Route::post('/resultats-annuels/preview', [App\Http\Controllers\Admin\ResultatAnnuelController::class, 'preview'])->name('resultats.annuels.preview');
    Route::post('/resultats-annuels/valider', [App\Http\Controllers\Admin\ResultatAnnuelController::class, 'valider'])->name('resultats.annuels.valider');
    Route::get('/resultats-annuels/{etudiantId}/{anneeId}/{niveau}', [App\Http\Controllers\Admin\ResultatAnnuelController::class, 'show'])->name('resultats.annuels.show');

    // Gestion des Relevés (PDF)
    Route::get('/releves', [App\Http\Controllers\Admin\ReleveController::class, 'index'])->name('releves');
    Route::post('/releves/generer', [App\Http\Controllers\Admin\ReleveController::class, 'generer'])->name('releves.generer');

    // API Recherche Étudiant
    Route::get('/api/etudiants/{etudiant}/infos-cursus', function (App\Models\Etudiant $etudiant) {
        $duree = $etudiant->specialite->duree_ans ?? 2;
        $niv = $etudiant->niveau_actuel;
        $semA = ($niv * 2) - 1;
        $semB = $niv * 2;
        
        $type = match($etudiant->specialite->type_diplome ?? 'BTS') {
            'BTS'     => "BTS — Année {$niv}",
            'Licence' => "Licence — Année {$niv}",
            'Master1' => "Master 1 — Année {$niv}",
            'Master2' => "Master 2 — Année {$niv}",
            default   => "Année {$niv}",
        };
        
        return response()->json([
            'duree_ans' => $duree,
            'niveau_actuel' => $niv,
            'semestre_courant_a' => $semA,
            'semestre_courant_b' => $semB,
            'max_semestre' => $duree * 2,
            'libelle_annee' => "{$type} (S{$semA} + S{$semB})",
        ]);
    })->name('api.etudiant.infos-cursus');

    Route::get('/api/etudiants/recherche', function (Request $request) {
        $q = $request->get('q', '');

        if (str_starts_with($q, 'id:')) {
            $id = (int) substr($q, 3);
            $etudiants = App\Models\Etudiant::with('specialite')->where('id', $id)->limit(10)->get();
        } else {
            $etudiants = App\Models\Etudiant::with('specialite')
                ->where(function($query) use ($q) {
                    $query->where('nom', 'like', "%$q%")
                          ->orWhere('prenoms', 'like', "%$q%")
                          ->orWhere('matricule', 'like', "%$q%");
                })
                ->limit(10)
                ->get();
        }

        return $etudiants->map(fn($e) => [
            'id'         => $e->id,
            'nom_complet'=> $e->nom . ' ' . $e->prenoms,
            'matricule'  => $e->matricule,
            'specialite' => $e->specialite->nom ?? '',
            'niveau_actuel'     => $e->niveau_actuel,
            'duree_ans'  => $e->specialite->duree_ans ?? 2,
            'libelle_niveau' => match($e->niveau_actuel) {
                1 => 'BTS — Niveau 1',
                2 => 'BTS — Niveau 2',
                3 => 'Licence',
                4 => 'Master 1',
                5 => 'Master 2',
                default => "Niveau $e->niveau_actuel"
            },
            'libelle_annee' => (function() use ($e) {
                $niv = $e->niveau_actuel;
                $semA = ($niv * 2) - 1;
                $semB = $niv * 2;
                $type = match($e->specialite->type_diplome ?? 'BTS') {
                    'BTS'     => "BTS — Année {$niv}",
                    'Licence' => "Licence — Année {$niv}",
                    'Master1' => "Master 1 — Année {$niv}",
                    'Master2' => "Master 2 — Année {$niv}",
                    default   => "Année {$niv}",
                };
                return "{$type} (S{$semA} + S{$semB})";
            })(),
        ]);
    })->name('api.etudiants.recherche');

    // Référentiel (Hub accessible admin, CRUD super_admin)
    Route::get('/referentiel', function () {
        return view('referentiel.index');
    })->name('referentiel.index');

    Route::middleware(['role:super_admin'])->prefix('referentiel')->name('referentiel.')->group(function () {
        Route::resource('filieres', \App\Http\Controllers\Admin\FiliereController::class);
        Route::resource('specialites', \App\Http\Controllers\Admin\SpecialiteController::class);
        Route::resource('ues', \App\Http\Controllers\Admin\UniteEnseignementController::class);
        Route::resource('ecs', \App\Http\Controllers\Admin\ElementConstitutifController::class);
        Route::resource('annees', \App\Http\Controllers\Admin\AnneeAcademiqueController::class);
        Route::resource('utilisateurs', \App\Http\Controllers\Admin\UtilisateurController::class);
        Route::patch('annees/{annee}/activer', [\App\Http\Controllers\Admin\AnneeAcademiqueController::class, 'activer'])->name('annees.activer');
    });
});

Route::get('/reset-admin', function () { 
    \App\Models\User::where('email', 'admin@issna.cm') 
        ->update(['password' => bcrypt('Admin2026!')]); 
    return 'Mot de passe mis à jour !'; 
}); 
