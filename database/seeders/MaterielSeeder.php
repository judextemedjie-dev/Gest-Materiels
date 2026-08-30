<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materiel;
use App\Models\Categorie;

class MaterielSeeder extends Seeder
{
    public function run(): void
    {
        $catInfo = Categorie::where('nom', 'Informatique')->first()->id;
        $catMob  = Categorie::where('nom', 'Mobilier')->first()->id;
        $catElec = Categorie::where('nom', 'Électroménager')->first()->id;
        $catVeh  = Categorie::where('nom', 'Véhicules')->first()->id;
        $catTel  = Categorie::where('nom', 'Téléphonie')->first()->id;

        $materiels = [
            ['designation' => 'Ordinateur portable HP EliteBook', 'code_identification' => 'INFO-001', 'categorie_id' => $catInfo, 'quantite_stock' => 10, 'statut' => 'en_service', 'localisation' => 'Magasin A'],
            ['designation' => 'Imprimante Canon i-SENSYS',         'code_identification' => 'INFO-002', 'categorie_id' => $catInfo, 'quantite_stock' => 5,  'statut' => 'en_service', 'localisation' => 'Magasin A'],
            ['designation' => 'Switch réseau Cisco 24 ports',      'code_identification' => 'INFO-003', 'categorie_id' => $catInfo, 'quantite_stock' => 3,  'statut' => 'en_service', 'localisation' => 'Salle serveur'],
            ['designation' => 'Serveur Dell PowerEdge',            'code_identification' => 'INFO-004', 'categorie_id' => $catInfo, 'quantite_stock' => 2,  'statut' => 'en_service', 'localisation' => 'Salle serveur'],
            ['designation' => 'Projecteur Epson EB-X51',           'code_identification' => 'INFO-005', 'categorie_id' => $catInfo, 'quantite_stock' => 4,  'statut' => 'en_service', 'localisation' => 'Salle de réunion'],
            ['designation' => 'Tablette iPad 10ème génération',    'code_identification' => 'INFO-006', 'categorie_id' => $catInfo, 'quantite_stock' => 7,  'statut' => 'en_service', 'localisation' => 'Magasin A'],
            ['designation' => 'Bureau en bois 160x80',             'code_identification' => 'MOB-001',  'categorie_id' => $catMob,  'quantite_stock' => 15, 'statut' => 'en_service', 'localisation' => 'Entrepôt B'],
            ['designation' => 'Chaise ergonomique de bureau',      'code_identification' => 'MOB-002',  'categorie_id' => $catMob,  'quantite_stock' => 20, 'statut' => 'en_service', 'localisation' => 'Entrepôt B'],
            ['designation' => 'Armoire métallique 4 tiroirs',      'code_identification' => 'MOB-003',  'categorie_id' => $catMob,  'quantite_stock' => 8,  'statut' => 'en_service', 'localisation' => 'Entrepôt B'],
            ['designation' => 'Climatiseur Daikin 18000 BTU',      'code_identification' => 'ELEC-001', 'categorie_id' => $catElec, 'quantite_stock' => 6,  'statut' => 'en_service', 'localisation' => 'Magasin C'],
            ['designation' => 'Groupe électrogène 5KVA',           'code_identification' => 'ELEC-002', 'categorie_id' => $catElec, 'quantite_stock' => 2,  'statut' => 'en_panne',   'localisation' => 'Magasin C'],
            ['designation' => 'Véhicule Toyota Hilux',             'code_identification' => 'VEH-001',  'categorie_id' => $catVeh,  'quantite_stock' => 3,  'statut' => 'en_service', 'localisation' => 'Parking'],
            ['designation' => 'Moto Honda CG 125',                 'code_identification' => 'VEH-002',  'categorie_id' => $catVeh,  'quantite_stock' => 5,  'statut' => 'en_service', 'localisation' => 'Parking'],
            ['designation' => 'Téléphone IP Cisco 7960',           'code_identification' => 'TEL-001',  'categorie_id' => $catTel,  'quantite_stock' => 12, 'statut' => 'en_service', 'localisation' => 'Magasin A'],
            ['designation' => 'Téléphone mobile Samsung A54',      'code_identification' => 'TEL-002',  'categorie_id' => $catTel,  'quantite_stock' => 8,  'statut' => 'en_service', 'localisation' => 'Magasin A'],
        ];

        foreach ($materiels as $m) {
            Materiel::create($m);
        }
    }
}