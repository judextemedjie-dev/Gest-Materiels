<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\{Maintenance, Materiel};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with('materiel')
            ->where('created_by', Auth::id());

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $maintenances = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('gestionnaire.maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        $materiels = Materiel::where('statut', '!=', 'archive')->orderBy('designation')->get();
        return view('gestionnaire.maintenances.create', compact('materiels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'materiel_id'    => 'required|exists:materiels,id',
            'type'           => 'required|in:controle,reparation,intervention',
            'date_planifiee' => 'required|date|after_or_equal:today',
            'technicien'     => 'nullable|string|max:255',
            'rapport'        => 'nullable|string',
        ], [
            'materiel_id.required'    => 'Veuillez sélectionner un matériel.',
            'type.required'           => 'Le type d\'intervention est obligatoire.',
            'date_planifiee.required' => 'La date planifiée est obligatoire.',
            'date_planifiee.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur.',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['statut']     = 'planifie';

        $maintenance = Maintenance::create($validated);

        // Si réparation → passer le matériel en "en_panne"
        if ($validated['type'] === 'reparation') {
            Materiel::find($validated['materiel_id'])->update(['statut' => 'en_panne']);
        }

        return redirect()->route('gestionnaire.maintenances.index')
            ->with('success', 'Maintenance planifiée avec succès.');
    }

    public function edit(Maintenance $maintenance)
    {
        $materiels = Materiel::where('statut', '!=', 'archive')->orderBy('designation')->get();
        return view('gestionnaire.maintenances.edit', compact('maintenance', 'materiels'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'statut'           => 'required|in:planifie,en_cours,termine',
            'date_realisation' => 'nullable|date',
            'technicien'       => 'nullable|string|max:255',
            'rapport'          => 'nullable|string',
            'cout'             => 'nullable|numeric|min:0',
        ]);

        $maintenance->update($validated);

        // Si terminée → repasser le matériel en "en_service"
        if ($validated['statut'] === 'termine') {
            $maintenance->materiel->update(['statut' => 'en_service']);
        }
        // Si en_cours → passer en réparation
        if ($validated['statut'] === 'en_cours' && $maintenance->type === 'reparation') {
            $maintenance->materiel->update(['statut' => 'en_reparation']);
        }

        return redirect()->route('gestionnaire.maintenances.index')
            ->with('success', 'Maintenance mise à jour avec succès.');
    }
}