<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Filiere;

class FiliereSeeder extends Seeder {
    public function run(): void {
        $filieres = [
            ['code' => 'F1', 'nom' => 'Sciences Infirmières'],
            ['code' => 'F2', 'nom' => 'Sage-Femme / Maïeutique'],
            ['code' => 'F3', 'nom' => 'Diététique et Nutrition'],
            ['code' => 'F4', 'nom' => 'Kinésithérapie'],
            ['code' => 'F5', 'nom' => 'Opticien-Lunetier'],
        ];
        foreach ($filieres as $f) {
            Filiere::create($f);
        }
    }
}
