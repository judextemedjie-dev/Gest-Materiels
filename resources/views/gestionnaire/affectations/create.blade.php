{{-- resources/views/gestionnaire/affectations/create.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Nouvelle affectation')
@section('page_title', 'Affecter un matériel')
@section('page_subtitle', 'Assigner du matériel à un client')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6"
         x-data="{
             selectedMateriel: null,
             materiels: {{ $materiels->map(fn($m) => ['id' => $m->id, 'designation' => $m->designation, 'stock' => $m->quantite_stock, 'code' => $m->code_identification])->toJson() }},
             quantite: 1,
             get stockDispo() { return this.selectedMateriel ? this.selectedMateriel.stock : 0; },
             get stockValide() { return this.selectedMateriel && this.quantite > 0 && this.quantite <= this.stockDispo; },
             setMateriel(id) {
                 this.selectedMateriel = this.materiels.find(m => m.id == id) || null;
                 this.quantite = 1;
             }
         }">

        <form method="POST" action="{{ route('gestionnaire.affectations.store') }}" class="space-y-5">
            @csrf

            {{-- Sélection Client --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Client <span class="text-red-500">*</span>
                </label>
                <select name="client_id" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('client_id') border-red-400 @enderror">
                    <option value="">— Sélectionner un client —</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}"
                            {{ old('client_id', request('client_id')) == $client->id ? 'selected' : '' }}>
                            {{ $client->nom }} ({{ $client->contact }})
                        </option>
                    @endforeach
                </select>
                @error('client_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                @if($clients->isEmpty())
                    <p class="text-amber-600 text-xs mt-1">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                        Aucun client. <a href="{{ route('gestionnaire.clients.create') }}" class="underline">Créer un client d'abord</a>
                    </p>
                @endif
            </div>

            {{-- Sélection Matériel --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Matériel <span class="text-red-500">*</span>
                </label>
                <select name="materiel_id" required
                        @change="setMateriel($event.target.value)"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('materiel_id') border-red-400 @enderror">
                    <option value="">— Sélectionner un matériel —</option>
                    @foreach($materiels as $m)
                        <option value="{{ $m->id }}"
                            {{ old('materiel_id', request('materiel_id')) == $m->id ? 'selected' : '' }}>
                            {{ $m->designation }} — Stock : {{ $m->quantite_stock }} u. ({{ $m->code_identification }})
                        </option>
                    @endforeach
                </select>
                @error('materiel_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                {{-- Info stock en temps réel --}}
                <div x-show="selectedMateriel" x-cloak class="mt-2 px-3 py-2 rounded-lg text-xs"
                     :class="stockDispo > 2 ? 'bg-teal-50 border border-teal-200 text-teal-700' : 'bg-amber-50 border border-amber-200 text-amber-700'">
                    <i class="fa-solid fa-boxes-stacked mr-1"></i>
                    Stock disponible : <strong x-text="stockDispo"></strong> unité(s)
                    <span x-show="stockDispo == 0" class="text-red-600 font-semibold ml-1">— RUPTURE DE STOCK</span>
                </div>
            </div>

            {{-- Quantité --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Quantité à affecter <span class="text-red-500">*</span>
                </label>
                <input type="number" name="quantite" required min="1"
                       x-model.number="quantite"
                       :max="stockDispo"
                       value="{{ old('quantite', 1) }}"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('quantite') border-red-400 @enderror">
                @error('quantite')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                {{-- Alerte quantité dépassée --}}
                <div x-show="selectedMateriel && quantite > stockDispo" x-cloak
                     class="mt-1 text-red-600 text-xs">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    Quantité demandée dépasse le stock disponible (<span x-text="stockDispo"></span> u.)
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Notes (optionnel)</label>
                <textarea name="notes" rows="2" placeholder="Raison de l'affectation, remarques..."
                          class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('notes') }}</textarea>
            </div>

            {{-- Info automatique --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-700">
                <i class="fa-solid fa-circle-info mr-1"></i>
                La date et l'heure d'affectation seront enregistrées automatiquement : <strong>{{ now()->format('d/m/Y à H:i:s') }}</strong>
            </div>

            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('gestionnaire.affectations.index') }}"
                   class="flex-1 text-center border border-slate-300 text-slate-700 py-2.5 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Annuler
                </a>
                <button type="submit"
                        :disabled="selectedMateriel && !stockValide"
                        :class="selectedMateriel && !stockValide ? 'opacity-50 cursor-not-allowed' : ''"
                        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-right-left mr-1"></i> Confirmer l'affectation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
