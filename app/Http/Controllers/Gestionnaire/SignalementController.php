<?php
// app/Http/Controllers/Gestionnaire/SignalementController.php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Signalement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignalementController extends Controller
{
    public function index()
    {
        // Signalements des clients de CE gestionnaire uniquement
        $signalements = Signalement::with(['client', 'materiel', 'affectation'])
            ->whereHas('client', fn($q) => $q->where('created_by', Auth::id()))
            ->orderByDesc('created_at')
            ->paginate(15);

        $nouveaux = Signalement::whereHas('client', fn($q) => $q->where('created_by', Auth::id()))
            ->where('statut', 'nouveau')
            ->count();

        return view('gestionnaire.signalements.index', compact('signalements', 'nouveaux'));
    }

    public function marquerLu(Signalement $signalement)
    {
        $this->autoriser($signalement);
        $signalement->update(['statut' => 'lu', 'lu_at' => now()]);
        return back()->with('success', 'Signalement marqué comme lu.');
    }

    public function marquerTraite(Signalement $signalement)
    {
        $this->autoriser($signalement);
        $signalement->update(['statut' => 'traite']);
        return back()->with('success', 'Signalement marqué comme traité.');
    }

    private function autoriser(Signalement $signalement): void
    {
        if ($signalement->client?->created_by !== Auth::id()) {
            abort(403);
        }
    }
}