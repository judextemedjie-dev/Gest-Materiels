{{-- resources/views/gestionnaire/rapports/inventaire.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Inventaire')
@section('page_title', 'Rapport Inventaire')
@section('page_subtitle', 'Généré le ' . now()->format('d/m/Y à H:i'))

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('gestionnaire.rapports.inventaire') }}" class="flex flex-wrap gap-2 items-center">
            <select name="categorie_id" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Toutes catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                @endforeach
            </select>
            <select name="statut" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Tous statuts</option>
                <option value="en_service"    {{ request('statut') == 'en_service' ? 'selected' : '' }}>En service</option>
                <option value="en_panne"      {{ request('statut') == 'en_panne' ? 'selected' : '' }}>En panne</option>
                <option value="en_reparation" {{ request('statut') == 'en_reparation' ? 'selected' : '' }}>En réparation</option>
            </select>
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded-lg text-sm">
                <i class="fa-solid fa-filter mr-1"></i> Filtrer
            </button>
        </form>
        <div class="flex gap-2">
            <a href="{{ route('gestionnaire.rapports.pdf', 'inventaire') }}"
               class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-2 rounded-lg flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-file-pdf"></i> Exporter PDF
            </a>
            <a href="{{ route('gestionnaire.rapports.excel', 'inventaire') }}"
               class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-2 rounded-lg flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-file-excel"></i> Exporter Excel
            </a>
        </div>
    </div>

    {{-- Résumé --}}
    <div class="px-6 py-3 bg-slate-50 border-b border-slate-200 flex gap-6 text-sm">
        <span class="text-slate-600">Total articles : <strong class="text-slate-800">{{ $materiels->count() }}</strong></span>
        <span class="text-slate-600">Quantité totale : <strong class="text-slate-800">{{ $total }}</strong> unités</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Désignation</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Code ID</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Catégorie</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Stock</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Statut</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Localisation</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($materiels as $m)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $m->designation }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $m->code_identification }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $m->categorie?->nom ?? '—' }}</td>
                    <td class="px-4 py-3 text-center font-bold {{ $m->quantite_stock <= 2 ? 'text-red-600' : 'text-slate-700' }}">{{ $m->quantite_stock }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs {{ $m->statut_badge }}">{{ $m->statut_label }}</span></td>
                    <td class="px-4 py-3 text-slate-500 text-xs">{{ $m->localisation ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Aucun matériel trouvé</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection