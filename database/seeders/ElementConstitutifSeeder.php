<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\ElementConstitutif;
use App\Models\UniteEnseignement;

class ElementConstitutifSeeder extends Seeder {
    public function run(): void {
        // SIN111 · Fondamentale · Total 8 crédits (2+2+2+2 répartition estimée)
        $ue1 = UniteEnseignement::where('code_ue', 'SIN111')->first()->id;
        $ec1 = [
            ['nom' => 'Anatomie et physiologie 1', 'credit' => 2, 'code_ec' => 'SIN111-1'],
            ['nom' => 'Anatomie et physiologie 2', 'credit' => 2, 'code_ec' => 'SIN111-2'],
            ['nom' => 'Anatomie et physiologie 3', 'credit' => 2, 'code_ec' => 'SIN111-3'],
            ['nom' => 'Pharmacologie générale',    'credit' => 2, 'code_ec' => 'SIN111-4'],
        ];
        foreach ($ec1 as $ec) {
            ElementConstitutif::create(array_merge($ec, ['ue_id' => $ue1]));
        }

        // SIN121 · Professionnelle · Total 19 crédits (répartition estimée)
        $ue2 = UniteEnseignement::where('code_ue', 'SIN121')->first()->id;
        $ec2 = [
            ['nom' => 'Philosophie des soins infirmiers',  'credit' => 2, 'code_ec' => 'SIN121-1'],
            ['nom' => 'Ethique et déontologie médicale',   'credit' => 2, 'code_ec' => 'SIN121-2'],
            ['nom' => 'Premiers secours',                  'credit' => 2, 'code_ec' => 'SIN121-3'],
            ['nom' => 'Techniques de soins en médecine',   'credit' => 5, 'code_ec' => 'SIN121-4'],
            ['nom' => 'Hygiène générale',                  'credit' => 2, 'code_ec' => 'SIN121-5'],
            ['nom' => 'Stage hospitalier',                 'credit' => 4, 'code_ec' => 'SIN121-6'],
            ['nom' => 'Méthodes de travail',               'credit' => 2, 'code_ec' => 'SIN121-7'],
        ];
        foreach ($ec2 as $ec) {
            ElementConstitutif::create(array_merge($ec, ['ue_id' => $ue2]));
        }

        // SIN131 · Transversale · Total 3 crédits (1+1+1)
        $ue3 = UniteEnseignement::where('code_ue', 'SIN131')->first()->id;
        $ec3 = [
            ['nom' => 'Français médical', 'credit' => 1, 'code_ec' => 'SIN131-1'],
            ['nom' => 'Anglais médical',  'credit' => 1, 'code_ec' => 'SIN131-2'],
            ['nom' => 'TIC',              'credit' => 1, 'code_ec' => 'SIN131-3'],
        ];
        foreach ($ec3 as $ec) {
            ElementConstitutif::create(array_merge($ec, ['ue_id' => $ue3]));
        }
    }
}
