<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\User;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $gest1 = User::where('email', 'gestionnaire1@gest.com')->first();
        $gest2 = User::where('email', 'gestionnaire2@gest.com')->first();

        $clients = [
            ['nom' => 'Direction Générale',    'email' => 'dg@institution.cm',          'contact' => '699001001', 'created_by' => $gest1->id],
            ['nom' => 'Service Comptabilité',  'email' => 'compta@institution.cm',       'contact' => '699001002', 'created_by' => $gest1->id],
            ['nom' => 'Service Informatique',  'email' => 'info@institution.cm',         'contact' => '699001003', 'created_by' => $gest1->id],
            ['nom' => 'Département RH',        'email' => 'rh@institution.cm',           'contact' => '699001004', 'created_by' => $gest2->id],
            ['nom' => 'Service Logistique',    'email' => 'logistique@institution.cm',   'contact' => '699001005', 'created_by' => $gest2->id],
        ];

        foreach ($clients as $c) {
            Client::create($c);
        }
    }
}