<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Materiel, Affectation, Client, User, Categorie, Maintenance, Mouvement};
use Carbon\Carbon;

class StatistiquesController extends Controller
{
    public function index()
    {
        // ---- Chiffres globaux
        $stats = [
            'total_materiels'       => Materiel::count(),
            'total_stock'           => Materiel::sum('quantite_stock'),
            'affectations_cours'    => Affectation::where('statut', 'affecte')->count(),
            'affectations_total'    => Affectation::count(),
            'affectations_restitues'=> Affectation::where('statut', 'restitue')->count(),
            'clients'               => Client::count(),
            'gestionnaires'         => User::role('gestionnaire')->count(),
            'maintenances_actives'  => Maintenance::where('statut', '!=', 'termine')->count(),
            'maintenances_retard'   => Maintenance::where('statut', '!=', 'termine')
                                        ->where('date_planifiee', '<', today())->count(),
        ];

        // ---- Taux de restitution
        $stats['taux_restitution'] = $stats['affectations_total'] > 0
            ? round(($stats['affectations_restitues'] / $stats['affectations_total']) * 100, 1)
            : 0;

        // ---- Affectations par mois (12 derniers mois)
        $affectationsParMois = [];
        $labelsParMois = [];
        for ($i = 11; $i >= 0; $i--) {
            $mois = Carbon::now()->subMonths($i);
            $labelsParMois[] = $mois->locale('fr')->isoFormat('MMM YY');
            $affectationsParMois[] = Affectation::whereYear('date_affectation', $mois->year)
                ->whereMonth('date_affectation', $mois->month)
                ->count();
        }

        // ---- Répartition par statut matériel
        $statutStats = Materiel::selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        // ---- Stock par catégorie
        $stockParCategorie = Categorie::withSum('materiels', 'quantite_stock')
            ->orderByDesc('materiels_sum_quantite_stock')
            ->get()
            ->map(fn($c) => [
                'nom'   => $c->nom,
                'stock' => $c->materiels_sum_quantite_stock ?? 0,
            ]);

        // ---- Top 5 matériels les plus affectés
        $topMateriels = Materiel::withCount('affectations')
            ->orderByDesc('affectations_count')
            ->limit(5)
            ->get();

        // ---- Top gestionnaires par activité
        $topGestionnaires = User::role('gestionnaire')
            ->withCount(['affectations', 'clients'])
            ->orderByDesc('affectations_count')
            ->get();

        // ---- Maintenances par type
        $maintenancesParType = Maintenance::selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        // ---- Stock critique
        $stockCritique = Materiel::where('quantite_stock', '<=', 2)
            ->where('statut', '!=', 'archive')
            ->orderBy('quantite_stock')
            ->get();

        // ---- Dernières opérations globales
        $dernieresOperations = Mouvement::with(['materiel', 'user', 'client'])
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        return view('admin.statistiques', compact(
            'stats',
            'affectationsParMois',
            'labelsParMois',
            'statutStats',
            'stockParCategorie',
            'topMateriels',
            'topGestionnaires',
            'maintenancesParType',
            'stockCritique',
            'dernieresOperations'
        ));
    }
}