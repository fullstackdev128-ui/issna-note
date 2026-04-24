<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Specialite;
use App\Models\Etudiant;

echo "=== A: FIX BIEGWEN AND SCIENCES INFIRMIERES ===\n";

// Fix SI: duree_ans = 1 for Licence
DB::table('specialites')->where('id', 1)->update(['duree_ans' => 1]);

// Fix BIEGWEN: niveau_actuel = 1
DB::table('etudiants')->where('id', 1)->update(['niveau_actuel' => 1]);

echo "Done.\n\n";

echo "=== B: VERIFY ALL SPECIALITES ===\n";
$results = DB::select("SELECT id, nom, type_diplome, duree_ans FROM specialites");
foreach ($results as $s) {
    echo "ID: {$s->id}, Nom: {$s->nom}, diplome: {$s->type_diplome}, duree: {$s->duree_ans}\n";
}

echo "\n=== C: FINAL VERIFICATION BIEGWEN ===\n";
$e = Etudiant::with('specialite')->find(1);
dump([
    'nom'           => $e->nom,
    'niveau_actuel' => $e->niveau_actuel,
    'duree_ans'     => $e->specialite->duree_ans,
    'type_diplome'  => $e->specialite->type_diplome,
    'max_semestre'  => $e->specialite->duree_ans * 2,
    'sem_courant_A' => ($e->niveau_actuel * 2) - 1,
    'sem_courant_B' => $e->niveau_actuel * 2,
]);

echo "\n=== ALL ETUDIANTS ===\n";
Etudiant::with('specialite')->get()->each(fn($e) => dump([
    'id' => $e->id,
    'nom' => $e->nom,
    'specialite' => $e->specialite->nom,
    'type_diplome' => $e->specialite->type_diplome,
    'duree_ans' => $e->specialite->duree_ans,
    'niveau' => $e->niveau_actuel,
]));
