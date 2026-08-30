{{-- resources/views/gestionnaire/materiels/show.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', $materiel->designation)
@section('page_title', $materiel->designation)
@section('page_subtitle', 'Fiche détaillée du matériel')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Fiche principale --}}
    <div class="xl:col-span-1 space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-box text-teal-600 text-xl"></i>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $materiel->statut_badge }}">
                    {{ $materiel->statut_label }}
                </span>
            </div>
            <h2 class="text-base font-bold text-slate-800 mb-1">{{ $materiel->designation }}</h2>
            <p class="font-mono text-xs text-slate-400 mb-4">{{ $materiel->code_identification }}</p>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Catégorie</span>
                    <span class="font-medium text-slate-700">{{ $materiel->categorie?->nom ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Stock disponible</span>
                    <span class="font-bold text-lg {{ $materiel->quantite_stock <= 2 ? 'text-red-600' : 'text-teal-600' }}">
                        {{ $materiel->quantite_stock }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Localisation</span>
                    <span class="font-medium text-slate-700">{{ $materiel->localisation ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Ajouté le</span>
                    <span class="text-slate-600">{{ $materiel->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            @if($materiel->description)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-xs text-slate-500 mb-1">Description</p>
                <p class="text-sm text-slate-600">{{ $materiel->description }}</p>
            </div>
            @endif
        </div>

        <div class="flex gap-2">
            <a href="{{ route('gestionnaire.materiels.edit', $materiel) }}"
               class="flex-1 text-center bg-amber-500 hover:bg-amber-600 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
                <i class="fa-solid fa-pen-to-square mr-1"></i> Modifier
            </a>
            <a href="{{ route('gestionnaire.affectations.create') }}?materiel_id={{ $materiel->id }}"
               class="flex-1 text-center bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
                <i class="fa-solid fa-right-left mr-1"></i> Affecter
            </a>
        </div>
    </div>

    {{-- Historique --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Affectations en cours --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">
                <i class="fa-solid fa-arrow-right text-blue-500 mr-2"></i>Affectations en cours
            </h3>
            @if($materiel->affectationsEnCours->isEmpty())
                <p class="text-sm text-slate-400 text-center py-4">Aucune affectation active</p>
            @else
                <div class="space-y-2">
                    @foreach($materiel->affectationsEnCours as $aff)
                    <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-slate-700">{{ $aff->client?->nom }}</p>
                            <p class="text-xs text-slate-500">Depuis le {{ $aff->date_affectation?->format('d/m/Y') }} à {{ $aff->heure_affectation }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">{{ $aff->quantite }}</p>
                            <p class="text-xs text-slate-400">unité(s)</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Historique des mouvements --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">
                <i class="fa-solid fa-clock-rotate-left text-slate-400 mr-2"></i>Historique des mouvements
            </h3>
            @if($materiel->mouvements->isEmpty())
                <p class="text-sm text-slate-400 text-center py-4">Aucun mouvement enregistré</p>
            @else
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($materiel->mouvements as $mvt)
                <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-slate-50">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5
                        {{ $mvt->type === 'affectation' ? 'bg-blue-100 text-blue-600' :
                           ($mvt->type === 'retour' ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-500') }}">
                        <i class="fa-solid {{ $mvt->type === 'affectation' ? 'fa-arrow-right' : ($mvt->type === 'retour' ? 'fa-arrow-left' : 'fa-circle') }} text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-700">{{ $mvt->type_label }} — {{ $mvt->quantite }} u.</p>
                        <p class="text-xs text-slate-400">{{ $mvt->description }} · {{ $mvt->user?->name }}</p>
                    </div>
                    <span class="text-xs text-slate-400 flex-shrink-0">{{ $mvt->created_at->format('d/m H:i') }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection