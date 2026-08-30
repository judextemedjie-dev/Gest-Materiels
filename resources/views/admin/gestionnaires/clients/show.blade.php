{{-- resources/views/gestionnaire/clients/show.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', $client->nom)
@section('page_title', $client->nom)
@section('page_subtitle', 'Fiche client complète')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Fiche client --}}
    <div class="xl:col-span-1 space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="text-center mb-5">
                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-teal-600 font-bold text-xl">{{ strtoupper(substr($client->nom, 0, 2)) }}</span>
                </div>
                <h2 class="text-base font-bold text-slate-800">{{ $client->nom }}</h2>
                <p class="text-xs text-slate-400 font-mono mt-1">#{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="space-y-3 text-sm border-t border-slate-100 pt-4">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-envelope text-slate-400 text-xs"></i>
                    </div>
                    <span class="text-slate-600 text-xs break-all">{{ $client->email }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-phone text-slate-400 text-xs"></i>
                    </div>
                    <span class="text-slate-600 text-xs">{{ $client->contact }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-calendar text-slate-400 text-xs"></i>
                    </div>
                    <span class="text-slate-600 text-xs">Créé le {{ $client->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <a href="{{ route('gestionnaire.affectations.create') }}?client_id={{ $client->id }}"
           class="block text-center bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
            <i class="fa-solid fa-right-left mr-2"></i> Assigner un matériel
        </a>
    </div>

    {{-- Affectations --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- En cours --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">
                <i class="fa-solid fa-arrow-right text-blue-500 mr-2"></i>Matériels actuellement assignés
                <span class="ml-2 bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">{{ $client->affectationsEnCours->count() }}</span>
            </h3>
            @if($client->affectationsEnCours->isEmpty())
                <div class="text-center py-6 text-slate-400">
                    <i class="fa-solid fa-box-open text-2xl mb-2"></i>
                    <p class="text-sm">Aucun matériel assigné en ce moment</p>
                </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-2 text-slate-500 font-medium">Désignation</th>
                            <th class="text-center py-2 text-slate-500 font-medium">Qté</th>
                            <th class="text-left py-2 text-slate-500 font-medium">Date assignation</th>
                            <th class="text-left py-2 text-slate-500 font-medium">Heure</th>
                            <th class="text-center py-2 text-slate-500 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($client->affectationsEnCours as $aff)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2 font-medium text-slate-700">{{ $aff->materiel?->designation }}</td>
                            <td class="py-2 text-center font-bold text-blue-600">{{ $aff->quantite }}</td>
                            <td class="py-2 text-slate-500">{{ $aff->date_affectation?->format('d/m/Y') }}</td>
                            <td class="py-2 text-slate-500">{{ $aff->heure_affectation }}</td>
                            <td class="py-2 text-center">
                                <form method="POST" action="{{ route('gestionnaire.affectations.restituer', $aff->id) }}"
                                      onsubmit="return confirm('Confirmer la restitution de {{ $aff->quantite }} unité(s) ?')">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-100 hover:bg-green-200 text-green-700 px-2.5 py-1 rounded text-xs transition-colors font-medium">
                                        <i class="fa-solid fa-rotate-left mr-1"></i> Restituer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Historique complet --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">
                <i class="fa-solid fa-clock-rotate-left text-slate-400 mr-2"></i>Historique complet des affectations
            </h3>
            @if($client->affectations->isEmpty())
                <p class="text-sm text-slate-400 text-center py-4">Aucun historique</p>
            @else
            <div class="overflow-x-auto max-h-72 overflow-y-auto">
                <table class="w-full text-xs">
                    <thead class="sticky top-0 bg-white">
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-2 text-slate-500 font-medium">Matériel</th>
                            <th class="text-center py-2 text-slate-500 font-medium">Qté</th>
                            <th class="text-left py-2 text-slate-500 font-medium">Assigné le</th>
                            <th class="text-left py-2 text-slate-500 font-medium">Restitué le</th>
                            <th class="text-left py-2 text-slate-500 font-medium">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($client->affectations as $aff)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2 font-medium text-slate-700">{{ Str::limit($aff->materiel?->designation, 25) }}</td>
                            <td class="py-2 text-center font-semibold">{{ $aff->quantite }}</td>
                            <td class="py-2 text-slate-500">{{ $aff->date_affectation?->format('d/m/Y') }}</td>
                            <td class="py-2 text-slate-500">{{ $aff->date_restitution?->format('d/m/Y') ?? '—' }}</td>
                            <td class="py-2">
                                <span class="px-2 py-0.5 rounded-full text-xs {{ $aff->statut_badge }}">{{ $aff->statut_label }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection