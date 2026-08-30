<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Informatique',   'description' => 'Ordinateurs, imprimantes, serveurs, accessoires'],
            ['nom' => 'Mobilier',       'description' => 'Bureaux, chaises, armoires, tables'],
            ['nom' => 'Électroménager', 'description' => 'Climatiseurs, réfrigérateurs, machines à café'],
            ['nom' => 'Véhicules',      'description' => 'Voitures, motos, camions de service'],
            ['nom' => 'Outillage',      'description' => 'Outils de maintenance et réparation'],
            ['nom' => 'Téléphonie',     'description' => 'Téléphones fixes, mobiles, visioconférence'],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }
    }
}