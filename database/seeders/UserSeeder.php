<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        User::create([
            'nom' => 'Super Admin',
            'prenoms' => 'ISSNA',
            'email' => 'admin@issna.cm',
            'password' => Hash::make('Issna@2026!'),
            'role' => 'super_admin',
            'actif' => true,
        ]);
        User::create([
            'nom' => 'Scolarité',
            'prenoms' => 'Admin',
            'email' => 'scolarite@issna.cm',
            'password' => Hash::make('Scol@2026!'),
            'role' => 'admin',
            'actif' => true,
        ]);
    }
}
