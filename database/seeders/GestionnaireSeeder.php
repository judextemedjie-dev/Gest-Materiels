<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class GestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        $gestionnaires = [
            ['name' => 'Jean-Pierre Mbarga', 'email' => 'gestionnaire1@gest.com'],
            ['name' => 'Marie Ekotto',        'email' => 'gestionnaire2@gest.com'],
        ];

        foreach ($gestionnaires as $g) {
            $user = User::create([
                'name'     => $g['name'],
                'email'    => $g['email'],
                'password' => Hash::make('Gest@2025'),
            ]);
            $user->assignRole('gestionnaire');
        }
    }
}