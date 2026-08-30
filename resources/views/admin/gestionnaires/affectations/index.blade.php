{{-- resources/views/gestionnaire/affectations/index.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Affectations')
@section('page_title', 'Affectations')
@section('page_subtitle', 'Suivi de toutes les affectations et restitutions')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200 flex flex-wrap gap-3 items-center justify-between">
        <form method="GET" action="{{ route('gestionnaire.affectations.index') }}" class="flex flex-wrap gap-2 items-center">
            <select name="statut" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Tous les statuts</option>
                <option value="affecte"  {{ request('statut') == 'affecte' ? 'selected' : '' }}>Affecté</option>
                <option value="restitue" {{ request('statut') == 'restitue' ? 'selected' : '' }}>Restitué</option>
            </select>
            <select name="client_id" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Tous les clients</option>
                @foreach($clients as $c)
                    <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                @endforeach
            </select>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                   class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                   class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filtrer
            </button>
            @if(request()->hasAny(['statut','client_id','date_debut','date_fin']))
            <a href="{{ route('gestionnaire.affectations.index') }}" class="text-slate-400 hover:text-slate-600 text-sm px-2">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </form>
        <a href="{{ route('gestionnaire.affectations.create') }}"
           class="bg-teal-600 hover:bg-teal-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-plus"></i> Nouvelle affectation
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Matériel</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Client</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Quantité</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date & Heure</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Restitué le</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Statut</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($affectations as $aff)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-800">{{ Str::limit($aff->materiel?->designation, 25) }}</p>
                        <p class="text-xs text-slate-400 font-mono">{{ $aff->materiel?->code_identification }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-700">{{ $aff->client?->nom }}</p>
                        <p class="text-xs text-slate-400">{{ $aff->client?->contact }}</p>
                    </td>
                    <td class="px-4 py-3 text-center font-bold text-slate-700">{{ $aff->quantite }}</td>
                    <td class="px-4 py-3">
                        <p class="text-slate-700">{{ $aff->date_affectation?->format('d/m/Y') }}</p>
                        <p class="text-xs text-slate-400">{{ $aff->heure_affectation }}</p>
                    </td>
                    <td class="px-4 py-3 text-slate-500 text-sm">{{ $aff->date_restitution?->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $aff->statut_badge }}">
                            {{ $aff->statut_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($aff->statut === 'affecte')
                        <form method="POST" action="{{ route('gestionnaire.affectations.restituer', $aff->id) }}"
                              onsubmit="return confirm('Confirmer la restitution de {{ $aff->quantite }} unité(s) de {{ $aff->materiel?->designation }} ?')">
                            @csrf
                            <button type="submit"
                                    class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Restituer
                            </button>
                        </form>
                        @else
                        <span class="text-slate-300 text-xs">Clôturé</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-right-left text-4xl mb-3"></i>
                        <p class="font-medium">Aucune affectation trouvée</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $affectations->links() }}</div>
</div>
@endsection