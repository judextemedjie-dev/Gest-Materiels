<?php
// app/Http/Controllers/Gestionnaire/MaterielController.php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\{Materiel, Categorie, Mouvement};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterielController extends Controller
{
    public function index(Request $request)
    {
        $query = Materiel::with('categorie')->where('statut', '!=', 'archive');

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('localisation')) {
            $query->where('localisation', 'like', "%{$request->localisation}%");
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('designation', 'like', "%{$request->search}%")
                  ->orWhere('code_identification', 'like', "%{$request->search}%");
            });
        }

        $materiels  = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        return view('gestionnaire.materiels.index', compact('materiels', 'categories'));
    }

    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('gestionnaire.materiels.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'designation'        => 'required|string|max:255',
            'code_identification'=> 'required|string|max:100|unique:materiels,code_identification',
            'categorie_id'       => 'required|exists:categories,id',
            'quantite_stock'     => 'required|integer|min:0',
            'statut'             => 'required|in:en_service,en_panne,en_reparation',
            'localisation'       => 'nullable|string|max:255',
            'description'        => 'nullable|string',
        ], [
            'designation.required'         => 'La désignation est obligatoire.',
            'code_identification.required' => 'Le code d\'identification est obligatoire.',
            'code_identification.unique'   => 'Ce code est déjà utilisé.',
            'categorie_id.required'        => 'La catégorie est obligatoire.',
            'quantite_stock.required'      => 'La quantité est obligatoire.',
        ]);

        $materiel = Materiel::create($validated);

        Mouvement::create([
            'materiel_id' => $materiel->id,
            'type'        => 'ajout',
            'quantite'    => $materiel->quantite_stock,
            'user_id'     => Auth::id(),
            'description' => 'Création et ajout au stock',
        ]);

        return redirect()->route('gestionnaire.materiels.index')
            ->with('success', "Matériel « {$materiel->designation} » ajouté avec succès.");
    }

    public function show(Materiel $materiel)
    {
        $materiel->load(['categorie', 'affectations.client', 'mouvements.user', 'maintenances']);
        return view('gestionnaire.materiels.show', compact('materiel'));
    }

    public function edit(Materiel $materiel)
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('gestionnaire.materiels.edit', compact('materiel', 'categories'));
    }

    public function update(Request $request, Materiel $materiel)
    {
        $validated = $request->validate([
            'designation'        => 'required|string|max:255',
            'code_identification'=> "required|string|max:100|unique:materiels,code_identification,{$materiel->id}",
            'categorie_id'       => 'required|exists:categories,id',
            'statut'             => 'required|in:en_service,en_panne,en_reparation,archive',
            'localisation'       => 'nullable|string|max:255',
            'description'        => 'nullable|string',
        ]);

        $materiel->update($validated);

        return redirect()->route('gestionnaire.materiels.index')
            ->with('success', "Matériel « {$materiel->designation} » mis à jour.");
    }

    public function destroy(Materiel $materiel)
    {
        if ($materiel->affectationsEnCours()->exists()) {
            return back()->with('error', 'Impossible d\'archiver : ce matériel a des affectations en cours.');
        }
        $materiel->update(['statut' => 'archive']);
        $materiel->delete(); // soft delete

        Mouvement::create([
            'materiel_id' => $materiel->id,
            'type'        => 'archivage',
            'quantite'    => 0,
            'user_id'     => Auth::id(),
            'description' => 'Archivage du matériel',
        ]);

        return redirect()->route('gestionnaire.materiels.index')
            ->with('success', "Matériel archivé avec succès.");
    }

    public function archiver(Materiel $materiel)
    {
        return $this->destroy($materiel);
    }
}