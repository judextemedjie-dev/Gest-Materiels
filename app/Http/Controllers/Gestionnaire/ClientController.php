<?php
// app/Http/Controllers/Gestionnaire/ClientController.php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::withCount(['affectationsEnCours as en_cours'])
            ->where('created_by', Auth::id());

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('contact', 'like', "%{$request->search}%");
            });
        }

        $clients = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('gestionnaire.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('gestionnaire.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'     => 'required|string|max:255',
            'email'   => 'required|email|unique:clients,email',
            'contact' => 'required|string|max:20',
        ], [
            'nom.required'     => 'Le nom est obligatoire.',
            'email.required'   => 'L\'email est obligatoire.',
            'email.unique'     => 'Cet email est déjà utilisé.',
            'contact.required' => 'Le contact est obligatoire.',
        ]);

        $validated['created_by'] = Auth::id();
        $client = Client::create($validated);

        return redirect()->route('gestionnaire.clients.show', $client)
            ->with('success', "Client « {$client->nom} » créé avec succès.");
    }

    public function show(Client $client)
    {
        $this->authorize_client($client);
        $client->load([
            'affectations.materiel',
            'affectationsEnCours.materiel',
            'mouvements.materiel',
        ]);
        return view('gestionnaire.clients.show', compact('client'));
    }

    private function authorize_client(Client $client): void
    {
        if ($client->created_by !== Auth::id()) {
            abort(403, 'Accès non autorisé à ce client.');
        }
    }
}