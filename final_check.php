<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Etudiant;
use App\Models\AnneeAcademique;
use App\Services\NoteCalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;

echo "GD Status: " . (extension_loaded('gd') ? 'GD OK' : 'GD MANQUANT') . "\n";

$etudiant = Etudiant::find(1);
$annee = AnneeAcademique::find(1);
$calculator = app(NoteCalculatorService::class);
$resultat = $calculator->calculerResultatSemestre(1, 1, 1);

echo "BIEGWEN S1 Average: " . $resultat['moyenne_sem'] . " (Expected 14.03)\n";

$data = [
    'etudiant' => $etudiant,
    'annee' => $annee->libelle,
    'semestres' => [[
        'numero' => 1,
        'resultat' => $resultat,
        'en_base' => null
    ]],
    'date' => date('d/m/Y')
];

try {
    $pdf = Pdf::loadView('releves.pdf', $data);
    $pdf->output();
    echo "PDF Generation: SUCCESS\n";
} catch (\Exception $e) {
    echo "PDF Generation: FAILED - " . $e->getMessage() . "\n";
}
