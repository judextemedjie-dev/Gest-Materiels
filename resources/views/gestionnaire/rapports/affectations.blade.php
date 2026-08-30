{{-- resources/views/gestionnaire/rapports/affectations.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Rapport Affectations')
@section('page_title', 'Rapport des Affectations')
@section('page_subtitle', 'Généré le ' . now()->format('d/m/Y à H:i'))

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('gestionnaire.rapports.affectations') }}" class="flex flex-wrap gap-2 items-center">
            <select name="statut" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Tous statuts</option>
                <option value="affecte"  {{ request('statut') == 'affecte'  ? 'selected' : '' }}>Affecté</option>
                <option value="restitue" {{ request('statut') == 'restitue' ? 'selected' : '' }}>Restitué</option>
            </select>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                   class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                   class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded-lg text-sm">
                <i class="fa-solid fa-filter mr-1"></i> Filtrer
            </button>
        </form>
        <div class="flex gap-2">
            <a href="{{ route('gestionnaire.rapports.pdf', 'affectations') }}"
               class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-2 rounded-lg flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('gestionnaire.rapports.excel', 'affectations') }}"
               class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-2 rounded-lg flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    <div class="px-6 py-3 bg-slate-50 border-b border-slate-200 flex gap-6 text-sm">
        <span class="text-slate-600">Total : <strong>{{ $affectations->count() }}</strong> affectations</span>
        <span class="text-slate-600">En cours : <strong class="text-blue-600">{{ $affectations->where('statut','affecte')->count() }}</strong></span>
        <span class="text-slate-600">Restituées : <strong class="text-green-600">{{ $affectations->where('statut','restitue')->count() }}</strong></span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Matériel</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Client</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Qté</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Date affectation</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Heure</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Date restitution</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($affectations as $aff)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-800">{{ Str::limit($aff->materiel?->designation, 25) }}</p>
                        <p class="text-xs text-slate-400 font-mono">{{ $aff->materiel?->code_identification }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-700">{{ $aff->client?->nom }}</p>
                        <p class="text-xs text-slate-400">{{ $aff->client?->contact }}</p>
                    </td>
                    <td class="px-4 py-3 text-center font-bold text-slate-700">{{ $aff->quantite }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $aff->date_affectation?->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-slate-500 text-xs">{{ $aff->heure_affectation }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $aff->date_restitution?->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $aff->statut_badge }}">{{ $aff->statut_label }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">Aucune affectation trouvée</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
