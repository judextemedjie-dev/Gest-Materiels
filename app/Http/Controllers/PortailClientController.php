<?php
// app/Http/Controllers/PortailClientController.php

namespace App\Http\Controllers;

use App\Models\{Client, Signalement, Affectation};
use App\Notifications\SignalementNotification;
use Illuminate\Http\Request;

class PortailClientController extends Controller
{
    /**
     * Page d'accueil du portail client (accessible via lien unique)
     */
    public function index(string $token)
    {
        $client = Client::where('token', $token)
            ->with([
                'affectationsEnCours.materiel',
                'affectations' => fn($q) => $q->orderByDesc('created_at')->limit(10),
                'affectations.materiel',
                'signalements.materiel',
            ])
            ->firstOrFail();

        return view('portail.index', compact('client', 'token'));
    }

    /**
     * Formulaire de signalement d'un problème
     */
    public function signalerForm(string $token, int $affectationId)
    {
        $client = Client::where('token', $token)->firstOrFail();

        $affectation = Affectation::with('materiel')
            ->where('client_id', $client->id)
            ->where('statut', 'affecte')
            ->findOrFail($affectationId);

        return view('portail.signaler', compact('client', 'token', 'affectation'));
    }

    /**
     * Enregistrement du signalement
     */
    public function signalerStore(Request $request, string $token, int $affectationId)
    {
        $client = Client::where('token', $token)->firstOrFail();

        $affectation = Affectation::with('materiel')
            ->where('client_id', $client->id)
            ->where('statut', 'affecte')
            ->findOrFail($affectationId);

        $validated = $request->validate([
            'type'        => 'required|in:panne,deterioration,perte,autre',
            'description' => 'required|string|min:10|max:1000',
        ], [
            'type.required'        => 'Veuillez choisir le type de problème.',
            'description.required' => 'La description est obligatoire.',
            'description.min'      => 'Décrivez le problème en au moins 10 caractères.',
        ]);

        // Créer le signalement
        $signalement = Signalement::create([
            'affectation_id' => $affectation->id,
            'client_id'      => $client->id,
            'materiel_id'    => $affectation->materiel_id,
            'type'           => $validated['type'],
            'description'    => $validated['description'],
            'statut'         => 'nouveau',
        ]);

        // Notifier le gestionnaire
        $gestionnaire = $affectation->gestionnaire;
        if ($gestionnaire) {
            $gestionnaire->notify(new SignalementNotification($signalement, $client, $affectation));
        }

        return redirect()
            ->route('portail.index', $token)
            ->with('success', 'Votre signalement a été envoyé au gestionnaire. Il sera traité rapidement.');
    }

    /**
     * Confirmation de réception du matériel
     */
    public function confirmerReception(string $token, int $affectationId)
    {
        $client = Client::where('token', $token)->firstOrFail();

        $affectation = Affectation::where('client_id', $client->id)
            ->where('statut', 'affecte')
            ->findOrFail($affectationId);

        // Juste enregistrer la confirmation (on ne restitue pas ici)
        // On pourrait ajouter un champ confirmed_at si besoin
        $affectation->touch(); // juste mettre à jour updated_at

        return redirect()
            ->route('portail.index', $token)
            ->with('success', 'Réception confirmée. Merci !');
    }
}