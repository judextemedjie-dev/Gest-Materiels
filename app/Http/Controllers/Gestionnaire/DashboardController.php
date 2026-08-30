<?php
// app/Http/Controllers/Gestionnaire/DashboardController.php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\{Materiel, Client, Affectation, Maintenance, Mouvement};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'materiels'          => Materiel::where('statut', '!=', 'archive')->count(),
            'affectations_cours' => Affectation::where('gestionnaire_id', $userId)
                                               ->where('statut', 'affecte')->count(),
            'clients'            => Client::where('created_by', $userId)->count(),
            'maintenances'       => Maintenance::whereHas('materiel')
                                               ->where('statut', '!=', 'termine')
                                               ->where('created_by', $userId)->count(),
        ];

        $affectationsRecentes = Affectation::with(['materiel', 'client'])
            ->where('gestionnaire_id', $userId)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $stockCritique = Materiel::where('quantite_stock', '<=', 2)
            ->where('statut', '!=', 'archive')
            ->orderBy('quantite_stock')
            ->get();

        $maintenancesPlanifiees = Maintenance::with('materiel')
            ->where('created_by', $userId)
            ->where('statut', '!=', 'termine')
            ->orderBy('date_planifiee')
            ->limit(5)
            ->get();

        // Graphique : affectations 30 derniers jours
        $affectationsParJour = Affectation::where('gestionnaire_id', $userId)
            ->where('date_affectation', '>=', Carbon::now()->subDays(30))
            ->selectRaw('date_affectation, COUNT(*) as total')
            ->groupBy('date_affectation')
            ->orderBy('date_affectation')
            ->pluck('total', 'date_affectation');

        return view('gestionnaire.dashboard', compact(
            'stats', 'affectationsRecentes', 'stockCritique',
            'maintenancesPlanifiees', 'affectationsParJour'
        ));
    }
}