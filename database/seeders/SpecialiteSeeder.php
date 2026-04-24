<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Specialite;
use App\Models\Filiere;

class SpecialiteSeeder extends Seeder {
    public function run(): void {
        $f1 = Filiere::where('code', 'F1')->first()->id;
        $f2 = Filiere::where('code', 'F2')->first()->id;
        $f3 = Filiere::where('code', 'F3')->first()->id;
        $f4 = Filiere::where('code', 'F4')->first()->id;
        $f5 = Filiere::where('code', 'F5')->first()->id;

        $specialites = [
            // Sciences Infirmières
            ['filiere_id' => $f1, 'code' => 'SI', 'nom' => 'Sciences Infirmières (Licence)', 'duree_ans' => 3],
            ['filiere_id' => $f1, 'code' => 'IDE', 'nom' => 'Infirmier Diplômé d\'État (BTS)', 'duree_ans' => 2],
            // Sage-Femme
            ['filiere_id' => $f2, 'code' => 'SF', 'nom' => 'Sage-Femme (BTS)', 'duree_ans' => 2],
            // Diététique
            ['filiere_id' => $f3, 'code' => 'DIE', 'nom' => 'Diététique et Nutrition (Licence)', 'duree_ans' => 3],
            // Kiné
            ['filiere_id' => $f4, 'code' => 'KINE', 'nom' => 'Kinésithérapie (Licence)', 'duree_ans' => 3],
            // Opticien (actif 2026-2027)
            ['filiere_id' => $f5, 'code' => 'OL', 'nom' => 'Opticien-Lunetier (BTS)', 'duree_ans' => 2, 'actif' => true],
        ];

        foreach ($specialites as $s) {
            Specialite::create($s);
        }
    }
}
