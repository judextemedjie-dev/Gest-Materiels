<?php
// app/Http/Controllers/Gestionnaire/AffectationController.php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\{Affectation, Materiel, Client, Mouvement};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use Carbon\Carbon;

class AffectationController extends Controller
{
    public function index(Request $request)
    {
        $query = Affectation::with(['materiel', 'client'])
            ->where('gestionnaire_id', Auth::id());

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('date_debut')) {
            $query->where('date_affectation', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->where('date_affectation', '<=', $request->date_fin);
        }

        $affectations = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $clients      = Client::where('created_by', Auth::id())->orderBy('nom')->get();

        return view('gestionnaire.affectations.index', compact('affectations', 'clients'));
    }

    public function create()
    {
        $clients   = Client::where('created_by', Auth::id())->orderBy('nom')->get();
        $materiels = Materiel::where('statut', 'en_service')
                             ->where('quantite_stock', '>', 0)
                             ->orderBy('designation')
                             ->get();

        return view('gestionnaire.affectations.create', compact('clients', 'materiels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'materiel_id' => 'required|exists:materiels,id',
            'client_id'   => 'required|exists:clients,id',
            'quantite'    => 'required|integer|min:1',
            'notes'       => 'nullable|string|max:500',
        ], [
            'materiel_id.required' => 'Veuillez sélectionner un matériel.',
            'client_id.required'   => 'Veuillez sélectionner un client.',
            'quantite.required'    => 'La quantité est obligatoire.',
            'quantite.min'         => 'La quantité doit être au moins 1.',
        ]);

        $materiel = Materiel::findOrFail($validated['materiel_id']);

        // Vérification stock suffisant
        if ($materiel->quantite_stock < $validated['quantite']) {
            return back()->withInput()->withErrors([
                'quantite' => "Stock insuffisant. Stock disponible : {$materiel->quantite_stock} unité(s)."
            ]);
        }

        // Vérification appartenance client au gestionnaire
        $client = Client::findOrFail($validated['client_id']);
        if ($client->created_by !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($validated, $materiel) {
            // Créer l'affectation
            Affectation::create([
                'materiel_id'       => $validated['materiel_id'],
                'client_id'         => $validated['client_id'],
                'gestionnaire_id'   => Auth::id(),
                'quantite'          => $validated['quantite'],
                'date_affectation'  => Carbon::now()->toDateString(),
                'heure_affectation' => Carbon::now()->toTimeString(),
                'statut'            => 'affecte',
                'notes'             => $validated['notes'] ?? null,
            ]);

            // Diminuer le stock
            $materiel->decrement('quantite_stock', $validated['quantite']);

            // Enregistrer le mouvement
            Mouvement::create([
                'materiel_id' => $validated['materiel_id'],
                'type'        => 'affectation',
                'quantite'    => $validated['quantite'],
                'user_id'     => Auth::id(),
                'client_id'   => $validated['client_id'],
                'description' => "Affectation de {$validated['quantite']} unité(s) au client #{$validated['client_id']}",
            ]);
        });

        return redirect()->route('gestionnaire.affectations.index')
            ->with('success', "Matériel affecté avec succès. Stock mis à jour.");
    }

    public function restituer(int $id)
    {
        $affectation = Affectation::with('materiel')
            ->where('gestionnaire_id', Auth::id())
            ->where('statut', 'affecte')
            ->findOrFail($id);

        DB::transaction(function () use ($affectation) {
            // Mettre à jour l'affectation
            $affectation->update([
                'statut'           => 'restitue',
                'date_restitution' => Carbon::now()->toDateString(),
            ]);

            // Augmenter le stock
            $affectation->materiel->increment('quantite_stock', $affectation->quantite);

            // Enregistrer le mouvement
            Mouvement::create([
                'materiel_id' => $affectation->materiel_id,
                'type'        => 'retour',
                'quantite'    => $affectation->quantite,
                'user_id'     => Auth::id(),
                'client_id'   => $affectation->client_id,
                'description' => "Restitution de {$affectation->quantite} unité(s) par client #{$affectation->client_id}",
            ]);
        });

        return redirect()->route('gestionnaire.affectations.index')
            ->with('success', "Matériel restitué. Stock mis à jour automatiquement.");
    }
}