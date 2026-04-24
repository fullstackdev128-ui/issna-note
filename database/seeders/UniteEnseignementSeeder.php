<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\UniteEnseignement;
use App\Models\Specialite;

class UniteEnseignementSeeder extends Seeder {
    public function run(): void {
        $si = Specialite::where('code', 'SI')->first()->id;

        // Semestre 1 - Niveau 1 (extrait du relevé réel ISSNA)
        UniteEnseignement::create([
            'specialite_id' => $si, 'code_ue' => 'SIN111',
            'nom' => 'Sciences Biologiques et Médicales',
            'type_ue' => 'Fondamentale', 'niveau' => 1, 'semestre' => 1
        ]);
        UniteEnseignement::create([
            'specialite_id' => $si, 'code_ue' => 'SIN121',
            'nom' => 'Sciences et Techniques Infirmières',
            'type_ue' => 'Professionnelle', 'niveau' => 1, 'semestre' => 1
        ]);
        UniteEnseignement::create([
            'specialite_id' => $si, 'code_ue' => 'SIN131',
            'nom' => 'Sciences Humaines et Communication',
            'type_ue' => 'Transversale', 'niveau' => 1, 'semestre' => 1
        ]);
        // Note : les autres semestres (S1 et S2 des autres niveaux) seront ajoutés après collecte
        // du référentiel complet avec la direction pédagogique.
    }
}
