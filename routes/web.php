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

Route::get('/debug-schema', function () {
    $cols = DB::select("SHOW COLUMNS FROM resultat_semestres LIKE 'decision_jury'");
    return response()->json($cols);
});

Route::get('/migrate-db', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return response()->json([
            'status' => 'success',
            'output' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

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

Route::get('/create-admin', function () { 
    \App\Models\User::updateOrCreate( 
        ['email' => 'rubens@issna.cm'], 
        [ 
            'nom' => 'Super Admin', 
            'prenoms' => 'Rubens', 
            'password' => bcrypt('Rubens2026!'), 
            'role' => 'super_admin', 
            'actif' => 1, 
        ] 
    ); 
    return 'Admin créé avec succès !'; 
}); 

Route::get('/create-campuses', function () { 
    \App\Models\Campus::firstOrCreate(
        ['nom' => 'Campus A'],
        ['ville' => 'Yaoundé']
    ); 
    \App\Models\Campus::firstOrCreate(
        ['nom' => 'Campus B'],
        ['ville' => 'Yaoundé']
    ); 
    return 'Campus A et Campus B créés avec succès !'; 
});

Route::get('/seed-all-data', function () { 
    // 5. annee_academiques
    $annee = \App\Models\AnneeAcademique::firstOrCreate(
        ['libelle' => '2025-2026'],
        ['active' => 1]
    );

    // 1. filieres
    $f1 = \App\Models\Filiere::firstOrCreate(['code' => 'F1'], ['nom' => 'Sciences Infirmières', 'actif' => 1]);
    $f2 = \App\Models\Filiere::firstOrCreate(['code' => 'F2'], ['nom' => 'Sage-Femme / Maïeutique', 'actif' => 1]);
    $f3 = \App\Models\Filiere::firstOrCreate(['code' => 'F3'], ['nom' => 'Diététique et Nutrition', 'actif' => 1]);
    $f4 = \App\Models\Filiere::firstOrCreate(['code' => 'F4'], ['nom' => 'Kinésithérapie', 'actif' => 1]);
    $f5 = \App\Models\Filiere::firstOrCreate(['code' => 'F5'], ['nom' => 'Opticien-Lunetier', 'actif' => 1]);

    // 2. specialites
    $si = \App\Models\Specialite::firstOrCreate(['code' => 'SI'], ['filiere_id' => $f1->id, 'nom' => 'Sciences Infirmières (Licence)', 'diplome' => 'Licence', 'duree_ans' => 3, 'type_diplome' => 'BTS', 'actif' => 1]);
    $ide = \App\Models\Specialite::firstOrCreate(['code' => 'IDE'], ['filiere_id' => $f1->id, 'nom' => "Infirmier Diplômé d'État (BTS)", 'diplome' => 'BTS', 'duree_ans' => 2, 'type_diplome' => 'BTS', 'actif' => 1]);
    $sf = \App\Models\Specialite::firstOrCreate(['code' => 'SF'], ['filiere_id' => $f2->id, 'nom' => 'Sage-Femme (BTS)', 'diplome' => 'BTS', 'duree_ans' => 2, 'type_diplome' => 'BTS', 'actif' => 1]);
    $die = \App\Models\Specialite::firstOrCreate(['code' => 'DIE'], ['filiere_id' => $f3->id, 'nom' => 'Diététique et Nutrition (Licence)', 'diplome' => 'Licence', 'duree_ans' => 3, 'type_diplome' => 'BTS', 'actif' => 1]);
    $kine = \App\Models\Specialite::firstOrCreate(['code' => 'KINE'], ['filiere_id' => $f4->id, 'nom' => 'Kinésithérapie (Licence)', 'diplome' => 'BTS', 'duree_ans' => 3, 'type_diplome' => 'BTS', 'actif' => 1]);
    $ol = \App\Models\Specialite::firstOrCreate(['code' => 'OL'], ['filiere_id' => $f5->id, 'nom' => 'Opticien-Lunetier (BTS)', 'diplome' => 'BTS', 'duree_ans' => 2, 'type_diplome' => 'BTS', 'actif' => 1]);

    // 3. & 4. unite_enseignements & element_constitutifs

    // KINE UEs
    $kine_ues = [
        ['code_ue' => 'KIN111', 'nom' => 'Biologie cellulaire - histologie - Anatomie Physiologie I', 'type_ue' => 'Fondamentale', 'niveau' => 1, 'semestre' => 1, 'credits' => 7, 'ecs' => ['Biologie cellulaire', 'Histologie', 'Anatomie Physiologie I']],
        ['code_ue' => 'KIN112', 'nom' => 'Neurophysiologie - Kinésiologie - Chimie générale', 'type_ue' => 'Fondamentale', 'niveau' => 1, 'semestre' => 1, 'credits' => 2, 'ecs' => ['Neurophysiologie', 'Kinésiologie', 'Chimie générale']],
        ['code_ue' => 'KIN113', 'nom' => 'Psychologie - Sociologie - Anthropologie générale - Histoire de la Kinésithérapie', 'type_ue' => 'Transversale', 'niveau' => 1, 'semestre' => 1, 'credits' => 4, 'ecs' => ['Psychologie et Sociologie', 'Anthropologie et Histoire de la Kinésithérapie']],
        ['code_ue' => 'KIN114', 'nom' => 'Méthodologie générale de la Kinésithérapie et de la réadaptation I', 'type_ue' => 'Professionnelle', 'niveau' => 1, 'semestre' => 1, 'credits' => 5, 'ecs' => ['Méthodologie générale de la Kinésithérapie', 'Réadaptation I']],
        ['code_ue' => 'KIN115', 'nom' => 'Maladies infectieuses et parasitaires (y compris les zoonoses)', 'type_ue' => 'Professionnelle', 'niveau' => 1, 'semestre' => 1, 'credits' => 5, 'ecs' => ['Maladies infectieuses', 'Parasitologie et zoonoses']],
        ['code_ue' => 'KIN116', 'nom' => 'Activités motrices et adaptation y compris la psychomotricité - Éthique et déontologie professionnelle', 'type_ue' => 'Professionnelle', 'niveau' => 1, 'semestre' => 1, 'credits' => 4, 'ecs' => ['Activités motrices et psychomotricité', 'Éthique et déontologie professionnelle']],
        ['code_ue' => 'KIN117', 'nom' => 'Français médical - Anglais médical - NTIC I', 'type_ue' => 'Transversale', 'niveau' => 1, 'semestre' => 1, 'credits' => 3, 'ecs' => ['Français médical', 'Anglais médical', 'NTIC I']],
        ['code_ue' => 'KIN121', 'nom' => 'Biomécanique - Anatomie physiologie II', 'type_ue' => 'Fondamentale', 'niveau' => 1, 'semestre' => 2, 'credits' => 6, 'ecs' => ['Biomécanique', 'Anatomie physiologie II']],
        ['code_ue' => 'KIN122', 'nom' => 'Pharmacologie générale et pharmacologie clinique', 'type_ue' => 'Fondamentale', 'niveau' => 1, 'semestre' => 2, 'credits' => 3, 'ecs' => ['Pharmacologie générale', 'Pharmacologie clinique']],
        ['code_ue' => 'KIN123', 'nom' => 'Stage clinique 1', 'type_ue' => 'Professionnelle', 'niveau' => 1, 'semestre' => 2, 'credits' => 6, 'ecs' => ['Pratique clinique 1', 'Rapport de stage 1']],
        ['code_ue' => 'KIN124', 'nom' => 'Épidémiologie - Bio statistiques - Santé et développement', 'type_ue' => 'Fondamentale', 'niveau' => 1, 'semestre' => 2, 'credits' => 7, 'ecs' => ['Épidémiologie', 'Bio statistiques', 'Santé et développement']],
        ['code_ue' => 'KIN125', 'nom' => 'Soins infirmiers et secourisme', 'type_ue' => 'Professionnelle', 'niveau' => 1, 'semestre' => 2, 'credits' => 3, 'ecs' => ['Soins infirmiers', 'Secourisme']],
        ['code_ue' => 'KIN126', 'nom' => 'Droit lié à la profession', 'type_ue' => 'Transversale', 'niveau' => 1, 'semestre' => 2, 'credits' => 2, 'ecs' => ['Droit médical', 'Législation professionnelle']],
        ['code_ue' => 'KIN127', 'nom' => 'Éducation civique et éthique', 'type_ue' => 'Transversale', 'niveau' => 1, 'semestre' => 2, 'credits' => 3, 'ecs' => ['Éducation civique', 'Éthique']],
        ['code_ue' => 'KIN231', 'nom' => 'Anatomie Physiologie III - Chimie physiologie', 'type_ue' => 'Fondamentale', 'niveau' => 2, 'semestre' => 3, 'credits' => 6, 'ecs' => ['Anatomie Physiologie III', 'Chimie physiologie']], 
        ['code_ue' => 'KIN232', 'nom' => 'Chimie minérale - Chimie organique', 'type_ue' => 'Fondamentale', 'niveau' => 2, 'semestre' => 3, 'credits' => 4, 'ecs' => ['Chimie minérale', 'Chimie organique']],
        ['code_ue' => 'KIN233', 'nom' => 'Stage clinique 2', 'type_ue' => 'Professionnelle', 'niveau' => 2, 'semestre' => 3, 'credits' => 6, 'ecs' => ['Pratique clinique 2', 'Rapport de stage 2']],
        ['code_ue' => 'KIN234', 'nom' => 'Méthodologie de kinésithérapie et réadaptation II', 'type_ue' => 'Professionnelle', 'niveau' => 2, 'semestre' => 3, 'credits' => 5, 'ecs' => ['Méthodologie de kinésithérapie II', 'Réadaptation II']],
        ['code_ue' => 'KIN235', 'nom' => 'Psychologie appliquée à la kinésithérapie', 'type_ue' => 'Transversale', 'niveau' => 2, 'semestre' => 3, 'credits' => 3, 'ecs' => ['Psychologie appliquée', 'Relation patient-thérapeute']],
        ['code_ue' => 'KIN236', 'nom' => 'Pathologies spéciales et kinésithérapie spécifique', 'type_ue' => 'Professionnelle', 'niveau' => 2, 'semestre' => 3, 'credits' => 4, 'ecs' => ['Pathologies spéciales', 'Kinésithérapie spécifique']],
        ['code_ue' => 'KIN237', 'nom' => 'Français - Anglais médical - TIC', 'type_ue' => 'Transversale', 'niveau' => 2, 'semestre' => 3, 'credits' => 2, 'ecs' => ['Français professionnel', 'Anglais médical II', 'TIC II']],
        ['code_ue' => 'KIN241', 'nom' => 'Économie générale - Gestion hospitalière - Initiat...', 'type_ue' => 'Transversale', 'niveau' => 2, 'semestre' => 4, 'credits' => 6, 'ecs' => ['Économie générale', 'Gestion hospitalière']],
    ];

    foreach ($kine_ues as $ueData) {
        $ue = \App\Models\UniteEnseignement::firstOrCreate(
            ['code_ue' => $ueData['code_ue']],
            [
                'specialite_id' => $kine->id,
                'nom' => $ueData['nom'],
                'type_ue' => $ueData['type_ue'],
                'niveau' => $ueData['niveau'],
                'semestre' => $ueData['semestre'],
                'credits' => $ueData['credits'],
            ]
        );
        $i = 1;
        foreach ($ueData['ecs'] as $ecNom) {
            \App\Models\ElementConstitutif::firstOrCreate(
                ['code_ec' => $ueData['code_ue'] . '-' . $i],
                [
                    'ue_id' => $ue->id,
                    'nom' => $ecNom,
                    'note_eliminatoire' => 8,
                    'credit' => max(1, floor($ueData['credits'] / count($ueData['ecs'])))
                ]
            );
            $i++;
        }
    }

    // SI UEs
    $sin111 = \App\Models\UniteEnseignement::firstOrCreate(['code_ue' => 'SIN111'], ['specialite_id' => $si->id, 'nom' => 'Sciences Biologiques et Médicales', 'type_ue' => 'Fondamentale', 'niveau' => 1, 'semestre' => 1, 'credits' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN111-1'], ['ue_id' => $sin111->id, 'nom' => 'Anatomie et physiologie 1', 'credit' => 2, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN111-2'], ['ue_id' => $sin111->id, 'nom' => 'Anatomie et physiologie 2', 'credit' => 2, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN111-3'], ['ue_id' => $sin111->id, 'nom' => 'Anatomie et physiologie 3', 'credit' => 2, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN111-4'], ['ue_id' => $sin111->id, 'nom' => 'Pharmacologie générale', 'credit' => 2, 'note_eliminatoire' => 8]);

    $sin121 = \App\Models\UniteEnseignement::firstOrCreate(['code_ue' => 'SIN121'], ['specialite_id' => $si->id, 'nom' => 'Sciences et Techniques Infirmières', 'type_ue' => 'Professionnelle', 'niveau' => 1, 'semestre' => 1, 'credits' => 17]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN121-1'], ['ue_id' => $sin121->id, 'nom' => 'Philosophie des soins infirmiers', 'credit' => 2, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN121-2'], ['ue_id' => $sin121->id, 'nom' => 'Éthique et déontologie médicale', 'credit' => 2, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN121-3'], ['ue_id' => $sin121->id, 'nom' => 'Premiers secours', 'credit' => 2, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN121-4'], ['ue_id' => $sin121->id, 'nom' => 'Techniques de soins en médecine', 'credit' => 5, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN121-5'], ['ue_id' => $sin121->id, 'nom' => 'Hygiène générale', 'credit' => 2, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN121-6'], ['ue_id' => $sin121->id, 'nom' => 'Stage hospitalier', 'credit' => 4, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN121-7'], ['ue_id' => $sin121->id, 'nom' => 'Méthodes de travail', 'credit' => 2, 'note_eliminatoire' => 8]);

    $sin131 = \App\Models\UniteEnseignement::firstOrCreate(['code_ue' => 'SIN131'], ['specialite_id' => $si->id, 'nom' => 'Sciences Humaines et Communication', 'type_ue' => 'Transversale', 'niveau' => 1, 'semestre' => 1, 'credits' => 3]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN131-1'], ['ue_id' => $sin131->id, 'nom' => 'Français médical', 'credit' => 1, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN131-2'], ['ue_id' => $sin131->id, 'nom' => 'Anglais médical', 'credit' => 1, 'note_eliminatoire' => 8]);
    \App\Models\ElementConstitutif::firstOrCreate(['code_ec' => 'SIN131-3'], ['ue_id' => $sin131->id, 'nom' => 'TIC', 'credit' => 1, 'note_eliminatoire' => 8]);

    // 6. etudiants
    $campusA = \App\Models\Campus::firstOrCreate(['nom' => 'Campus A'], ['ville' => 'Yaoundé']);
    $campusB = \App\Models\Campus::firstOrCreate(['nom' => 'Campus B'], ['ville' => 'Yaoundé']);
    
    // Etudiant 1 (Martin NOAH)
    \App\Models\Etudiant::firstOrCreate(
        ['matricule' => '26KI0001'],
        [
            'nom' => 'NOAH',
            'prenoms' => 'Martin',
            'date_naissance' => '2002-12-30',
            'lieu_naissance' => 'Bertoa',
            'genre' => 'M',
            'telephone' => '699854124',
            'email' => 'noahmartin@gmail.com',
            'lieu_residence' => 'Bonamoussadi',
            'etablissement_provenance' => 'IUGET',
            'nom_parent' => 'Bilo\'o Christian',
            'tel_parent' => '677852145',
            'campus_id' => $campusA->id,
            'specialite_id' => $kine->id,
            'niveau_actuel' => 3,
            'annee_acad_id' => $annee->id,
            'date_inscription' => now(),
            'statut' => 'actif'
        ]
    );

    // Etudiant 2
    \App\Models\Etudiant::firstOrCreate(
        ['matricule' => '26SI0002'],
        [
            'nom' => 'EKANI',
            'prenoms' => 'Jeanne',
            'date_naissance' => '2003-05-15',
            'lieu_naissance' => 'Yaoundé',
            'genre' => 'F',
            'telephone' => '677112233',
            'email' => 'jekani@gmail.com',
            'lieu_residence' => 'Bastos',
            'etablissement_provenance' => 'Lycée Leclerc',
            'nom_parent' => 'Ekani Pierre',
            'tel_parent' => '699223344',
            'campus_id' => $campusB->id,
            'specialite_id' => $si->id,
            'niveau_actuel' => 1,
            'annee_acad_id' => $annee->id,
            'date_inscription' => now(),
            'statut' => 'actif'
        ]
    );

    // Etudiant 3
    \App\Models\Etudiant::firstOrCreate(
        ['matricule' => '26SF0003'],
        [
            'nom' => 'FOTSO',
            'prenoms' => 'Alain',
            'date_naissance' => '2001-08-22',
            'lieu_naissance' => 'Douala',
            'genre' => 'M',
            'telephone' => '655998877',
            'email' => 'afotso@gmail.com',
            'lieu_residence' => 'Deido',
            'etablissement_provenance' => 'Collège De La Salle',
            'nom_parent' => 'Fotso Jean',
            'tel_parent' => '677889900',
            'campus_id' => $campusA->id,
            'specialite_id' => $sf->id,
            'niveau_actuel' => 2,
            'annee_acad_id' => $annee->id,
            'date_inscription' => now(),
            'statut' => 'actif'
        ]
    );
    
    return 'L\'ensemble des données a été injecté avec succès !';
});
