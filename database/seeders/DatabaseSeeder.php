<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            CampusSeeder::class,
            AnneeAcademiqueSeeder::class,
            FiliereSeeder::class,
            SpecialiteSeeder::class,
            UniteEnseignementSeeder::class,
            ElementConstitutifSeeder::class,
            UserSeeder::class,
            KineSeeder::class,
        ]);
    }
}
