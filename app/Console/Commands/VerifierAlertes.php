<?php

namespace App\Console\Commands;

use App\Models\{Materiel, Maintenance, User};
use App\Notifications\{StockCritiqueNotification, MaintenanceRetardNotification};
use Illuminate\Console\Command;

class VerifierAlertes extends Command
{
    protected $signature   = 'alertes:verifier';
    protected $description = 'Vérifie les stocks critiques et maintenances en retard';

    public function handle(): void
    {
        $gestionnaires = User::role('gestionnaire')->get();

        // ---- Stock critique (≤ 2 unités)
        $stockCritiques = Materiel::where('quantite_stock', '<=', 2)
                                  ->where('statut', '!=', 'archive')
                                  ->get();

        foreach ($stockCritiques as $materiel) {
            foreach ($gestionnaires as $gestionnaire) {
                // Éviter les doublons : ne notifier que si pas déjà notifié aujourd'hui
                $dejaNotifie = $gestionnaire->notifications()
                    ->whereDate('created_at', today())
                    ->where('data->type', 'stock_critique')
                    ->where('data->materiel_id', $materiel->id)
                    ->exists();

                if (! $dejaNotifie) {
                    $gestionnaire->notify(new StockCritiqueNotification($materiel));
                }
            }
        }

        // ---- Maintenances en retard
        $maintenancesEnRetard = Maintenance::with('materiel')
            ->where('statut', '!=', 'termine')
            ->where('date_planifiee', '<', today())
            ->get();

        foreach ($maintenancesEnRetard as $maintenance) {
            $gestionnaire = User::find($maintenance->created_by);
            if (! $gestionnaire) continue;

            $dejaNotifie = $gestionnaire->notifications()
                ->whereDate('created_at', today())
                ->where('data->type', 'maintenance_retard')
                ->where('data->maintenance_id', $maintenance->id)
                ->exists();

            if (! $dejaNotifie) {
                $gestionnaire->notify(new MaintenanceRetardNotification($maintenance));
            }
        }

        $this->info('✓ Vérification alertes terminée — '
            . $stockCritiques->count() . ' stock(s) critique(s), '
            . $maintenancesEnRetard->count() . ' maintenance(s) en retard.');
    }
}