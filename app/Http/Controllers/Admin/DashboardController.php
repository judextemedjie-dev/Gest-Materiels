<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Client, Materiel, Affectation, Mouvement};
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'gestionnaires'     => User::role('gestionnaire')->count(),
            'clients'           => Client::count(),
            'materiels'         => Materiel::count(),
            'affectations_cours'=> Affectation::where('statut', 'affecte')->count(),
        ];

        $activiteRecente = Mouvement::with(['materiel', 'user', 'client'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $stockCritique = Materiel::where('quantite_stock', '<=', 2)
            ->where('statut', '!=', 'archive')
            ->get();

        $statutStats = Materiel::selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $gestionnaires = User::role('gestionnaire')
            ->withCount(['affectations', 'clients'])
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'activiteRecente', 'stockCritique', 'statutStats', 'gestionnaires'
        ));
    }
}