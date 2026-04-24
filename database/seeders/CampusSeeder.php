<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Campus;

class CampusSeeder extends Seeder {
    public function run(): void {
        Campus::create(['nom' => 'Campus A', 'ville' => 'Douala', 'adresse' => 'Douala, Cameroun']);
        Campus::create(['nom' => 'Campus B', 'ville' => 'Douala', 'adresse' => 'Douala, Cameroun']);
    }
}
