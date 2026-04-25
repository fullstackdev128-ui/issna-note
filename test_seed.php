<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
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
    
    echo "SI created.\n";

    echo "Toutes les donnees de base ont ete creees avec succes !\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
