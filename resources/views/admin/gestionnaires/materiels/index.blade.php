{{-- resources/views/gestionnaire/materiels/index.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Matériels')
@section('page_title', 'Gestion des matériels')
@section('page_subtitle', 'Inventaire complet du parc matériel')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('gestionnaire.materiels.index') }}" class="flex flex-wrap gap-2 items-center">
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
                       class="pl-8 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 w-48">
            </div>
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
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filtrer
            </button>
            @if(request()->hasAny(['search','categorie_id','statut']))
            <a href="{{ route('gestionnaire.materiels.index') }}" class="text-slate-400 hover:text-slate-600 text-sm px-2">
                <i class="fa-solid fa-xmark"></i> Effacer
            </a>
            @endif
        </form>
        <a href="{{ route('gestionnaire.materiels.create') }}"
           class="bg-teal-600 hover:bg-teal-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-plus"></i> Ajouter un matériel
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Désignation</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Code ID</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Catégorie</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Stock</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Statut</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Localisation</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($materiels as $m)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $m->designation }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-slate-500 bg-slate-50">{{ $m->code_identification }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $m->categorie?->nom ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-base font-bold {{ $m->quantite_stock <= 2 ? 'text-red-600' : 'text-slate-700' }}">
                            {{ $m->quantite_stock }}
                        </span>
                        @if($m->quantite_stock <= 2 && $m->quantite_stock > 0)
                            <span class="ml-1 text-amber-500 text-xs"><i class="fa-solid fa-triangle-exclamation"></i></span>
                        @elseif($m->quantite_stock == 0)
                            <span class="ml-1 text-red-500 text-xs"><i class="fa-solid fa-circle-xmark"></i></span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $m->statut_badge }}">
                            {{ $m->statut_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-500 text-xs">{{ $m->localisation ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('gestionnaire.materiels.show', $m) }}"
                               class="text-blue-500 hover:text-blue-700 transition-colors" title="Détails">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('gestionnaire.materiels.edit', $m) }}"
                               class="text-amber-500 hover:text-amber-700 transition-colors" title="Modifier">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="{{ route('gestionnaire.materiels.destroy', $m) }}"
                                  onsubmit="return confirm('Archiver ce matériel ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors" title="Archiver">
                                    <i class="fa-solid fa-box-archive"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-box-open text-4xl mb-3"></i>
                        <p class="font-medium">Aucun matériel trouvé</p>
                        <a href="{{ route('gestionnaire.materiels.create') }}" class="text-teal-600 hover:underline text-sm mt-1 inline-block">
                            Ajouter le premier matériel
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $materiels->links() }}
    </div>
</div>
@endsection