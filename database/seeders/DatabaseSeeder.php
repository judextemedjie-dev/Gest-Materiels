<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            AdminSeeder::class,
            CategorieSeeder::class,
            GestionnaireSeeder::class,
            MaterielSeeder::class,
            ClientSeeder::class,
            AffectationSeeder::class,
        ]);
    }
}