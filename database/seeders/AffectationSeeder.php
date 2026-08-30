<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Affectation;
use App\Models\Client;
use App\Models\Materiel;
use App\Models\Mouvement;
use App\Models\User;
use Carbon\Carbon;

class AffectationSeeder extends Seeder
{
    public function run(): void
    {
        $gest1   = User::where('email', 'gestionnaire1@gest.com')->first();
        $gest2   = User::where('email', 'gestionnaire2@gest.com')->first();
        $client1 = Client::where('email', 'dg@institution.cm')->first();
        $client2 = Client::where('email', 'compta@institution.cm')->first();
        $client3 = Client::where('email', 'rh@institution.cm')->first();
        $laptop  = Materiel::where('code_identification', 'INFO-001')->first();
        $printer = Materiel::where('code_identification', 'INFO-002')->first();
        $bureau  = Materiel::where('code_identification', 'MOB-001')->first();
        $chaise  = Materiel::where('code_identification', 'MOB-002')->first();
        $clim    = Materiel::where('code_identification', 'ELEC-001')->first();

        // Affectation 1 — en cours
        Affectation::create([
            'materiel_id'       => $laptop->id,
            'client_id'         => $client1->id,
            'gestionnaire_id'   => $gest1->id,
            'quantite'          => 3,
            'date_affectation'  => Carbon::now()->subDays(10)->toDateString(),
            'heure_affectation' => Carbon::now()->subDays(10)->toTimeString(),
            'statut'            => 'affecte',
            'notes'             => 'Direction générale - usage administratif',
        ]);
        $laptop->decrement('quantite_stock', 3);
        Mouvement::create(['materiel_id' => $laptop->id, 'type' => 'affectation', 'quantite' => 3, 'user_id' => $gest1->id, 'client_id' => $client1->id, 'description' => 'Affectation direction générale']);

        // Affectation 2 — en cours
        Affectation::create([
            'materiel_id'       => $printer->id,
            'client_id'         => $client2->id,
            'gestionnaire_id'   => $gest1->id,
            'quantite'          => 2,
            'date_affectation'  => Carbon::now()->subDays(5)->toDateString(),
            'heure_affectation' => Carbon::now()->subDays(5)->toTimeString(),
            'statut'            => 'affecte',
            'notes'             => 'Comptabilité - impression bilans',
        ]);
        $printer->decrement('quantite_stock', 2);
        Mouvement::create(['materiel_id' => $printer->id, 'type' => 'affectation', 'quantite' => 2, 'user_id' => $gest1->id, 'client_id' => $client2->id, 'description' => 'Affectation comptabilité']);

        // Affectation 3 — restituée
        Affectation::create([
            'materiel_id'       => $bureau->id,
            'client_id'         => $client3->id,
            'gestionnaire_id'   => $gest2->id,
            'quantite'          => 5,
            'date_affectation'  => Carbon::now()->subDays(30)->toDateString(),
            'heure_affectation' => Carbon::now()->subDays(30)->toTimeString(),
            'statut'            => 'restitue',
            'date_restitution'  => Carbon::now()->subDays(2)->toDateString(),
        ]);
        Mouvement::create(['materiel_id' => $bureau->id, 'type' => 'affectation', 'quantite' => 5, 'user_id' => $gest2->id, 'client_id' => $client3->id, 'description' => 'Affectation RH']);
        Mouvement::create(['materiel_id' => $bureau->id, 'type' => 'retour',      'quantite' => 5, 'user_id' => $gest2->id, 'client_id' => $client3->id, 'description' => 'Restitution RH']);

        // Affectation 4 — en cours
        Affectation::create([
            'materiel_id'       => $chaise->id,
            'client_id'         => $client1->id,
            'gestionnaire_id'   => $gest1->id,
            'quantite'          => 8,
            'date_affectation'  => Carbon::now()->subDays(7)->toDateString(),
            'heure_affectation' => Carbon::now()->subDays(7)->toTimeString(),
            'statut'            => 'affecte',
        ]);
        $chaise->decrement('quantite_stock', 8);
        Mouvement::create(['materiel_id' => $chaise->id, 'type' => 'affectation', 'quantite' => 8, 'user_id' => $gest1->id, 'client_id' => $client1->id, 'description' => 'Chaises direction générale']);

        // Affectation 5 — en cours
        Affectation::create([
            'materiel_id'       => $clim->id,
            'client_id'         => $client2->id,
            'gestionnaire_id'   => $gest2->id,
            'quantite'          => 2,
            'date_affectation'  => Carbon::now()->subDays(3)->toDateString(),
            'heure_affectation' => Carbon::now()->subDays(3)->toTimeString(),
            'statut'            => 'affecte',
        ]);
        $clim->decrement('quantite_stock', 2);
        Mouvement::create(['materiel_id' => $clim->id, 'type' => 'affectation', 'quantite' => 2, 'user_id' => $gest2->id, 'client_id' => $client2->id, 'description' => 'Climatisation comptabilité']);
    }
}