{{-- resources/views/gestionnaire/dashboard.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Dashboard')
@section('page_title', 'Tableau de bord')
@section('page_subtitle', 'Bienvenue, ' . Auth::user()->name)

@section('content')

{{-- Cartes stats --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-boxes-stacked text-violet-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['materiels'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Matériels en stock</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-right-left text-blue-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['affectations_cours'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Affectations en cours</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-building-user text-teal-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['clients'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Clients actifs</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-screwdriver-wrench text-orange-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['maintenances'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Maintenances actives</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

    {{-- Affectations récentes --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-700">
                <i class="fa-solid fa-clock-rotate-left text-slate-400 mr-2"></i>Mes dernières affectations
            </h3>
            <a href="{{ route('gestionnaire.affectations.index') }}" class="text-xs text-blue-600 hover:underline">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left py-2 text-slate-500 font-medium">Matériel</th>
                        <th class="text-left py-2 text-slate-500 font-medium">Client</th>
                        <th class="text-center py-2 text-slate-500 font-medium">Qté</th>
                        <th class="text-left py-2 text-slate-500 font-medium">Date & Heure</th>
                        <th class="text-left py-2 text-slate-500 font-medium">Statut</th>
                        <th class="text-center py-2 text-slate-500 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($affectationsRecentes as $aff)
                    <tr class="hover:bg-slate-50">
                        <td class="py-2 font-medium text-slate-700">{{ Str::limit($aff->materiel?->designation, 22) }}</td>
                        <td class="py-2 text-slate-600">{{ Str::limit($aff->client?->nom, 18) }}</td>
                        <td class="py-2 text-center font-semibold text-slate-700">{{ $aff->quantite }}</td>
                        <td class="py-2 text-slate-500">{{ $aff->date_affectation?->format('d/m/Y') }} {{ $aff->heure_affectation }}</td>
                        <td class="py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $aff->statut_badge }}">
                                {{ $aff->statut_label }}
                            </span>
                        </td>
                        <td class="py-2 text-center">
                            @if($aff->statut === 'affecte')
                            <form method="POST" action="{{ route('gestionnaire.affectations.restituer', $aff->id) }}"
                                  onsubmit="return confirm('Confirmer la restitution ?')">
                                @csrf
                                <button type="submit" class="bg-green-100 hover:bg-green-200 text-green-700 px-2 py-1 rounded text-xs transition-colors">
                                    Restituer
                                </button>
                            </form>
                            @else
                            <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-6 text-center text-slate-400">Aucune affectation</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Stock critique --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-2"></i>Stock critique
        </h3>
        @if($stockCritique->isEmpty())
            <div class="text-center py-8 text-slate-400">
                <i class="fa-solid fa-circle-check text-3xl text-green-400 mb-2"></i>
                <p class="text-sm">Tout est OK</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($stockCritique as $m)
                <div class="flex items-center justify-between py-2 px-3 rounded-lg
                    {{ $m->quantite_stock == 0 ? 'bg-red-50 border border-red-200' : 'bg-amber-50 border border-amber-200' }}">
                    <div class="min-w-0 mr-2">
                        <p class="text-xs font-medium text-slate-700 truncate">{{ $m->designation }}</p>
                        <p class="text-xs text-slate-400">{{ $m->code_identification }}</p>
                    </div>
                    <span class="text-base font-bold flex-shrink-0 {{ $m->quantite_stock == 0 ? 'text-red-600' : 'text-amber-600' }}">
                        {{ $m->quantite_stock }}
                    </span>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Maintenances planifiées --}}
@if($maintenancesPlanifiees->isNotEmpty())
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
    <h3 class="text-sm font-semibold text-slate-700 mb-4">
        <i class="fa-solid fa-calendar-check text-blue-500 mr-2"></i>Maintenances à venir
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
        @foreach($maintenancesPlanifiees as $m)
        <div class="border rounded-lg p-3 {{ $m->estEnRetard() ? 'border-red-200 bg-red-50' : 'border-slate-200 bg-slate-50' }}">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-700">{{ Str::limit($m->materiel?->designation, 25) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $m->type }} · {{ $m->technicien ?? 'N/A' }}</p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $m->statut_badge }}">{{ $m->statut_label }}</span>
            </div>
            <p class="text-xs mt-2 {{ $m->estEnRetard() ? 'text-red-600 font-medium' : 'text-slate-400' }}">
                <i class="fa-regular fa-calendar mr-1"></i>{{ $m->date_planifiee?->format('d/m/Y') }}
                {{ $m->estEnRetard() ? '⚠ En retard' : '' }}
            </p>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
