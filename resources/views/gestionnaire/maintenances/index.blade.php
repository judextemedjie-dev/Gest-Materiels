{{-- resources/views/gestionnaire/maintenances/index.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Maintenances')
@section('page_title', 'Maintenance')
@section('page_subtitle', 'Suivi des interventions')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-4 lg:px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row gap-3">
        <form method="GET" action="{{ route('gestionnaire.maintenances.index') }}" class="flex flex-wrap gap-2 items-center flex-1">
            <select name="statut" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 flex-1 sm:flex-none">
                <option value="">Tous statuts</option>
                <option value="planifie"  {{ request('statut') == 'planifie' ? 'selected' : '' }}>Planifié</option>
                <option value="en_cours"  {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="termine"   {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
            </select>
            <select name="type" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 flex-1 sm:flex-none">
                <option value="">Tous types</option>
                <option value="controle"     {{ request('type') == 'controle' ? 'selected' : '' }}>Contrôle</option>
                <option value="reparation"   {{ request('type') == 'reparation' ? 'selected' : '' }}>Réparation</option>
                <option value="intervention" {{ request('type') == 'intervention' ? 'selected' : '' }}>Intervention</option>
            </select>
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded-lg text-sm">
                <i class="fa-solid fa-filter"></i>
            </button>
        </form>
        <a href="{{ route('gestionnaire.maintenances.create') }}"
           class="bg-teal-600 hover:bg-teal-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors whitespace-nowrap self-start">
            <i class="fa-solid fa-plus"></i> Planifier
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" style="min-width:550px">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Matériel</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase hidden sm:table-cell">Type</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Statut</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Date</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase hidden md:table-cell">Technicien</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase hidden lg:table-cell">Coût</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($maintenances as $m)
                <tr class="hover:bg-slate-50 {{ $m->estEnRetard() ? 'bg-red-50/30' : '' }}">
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-800">{{ Str::limit($m->materiel?->designation, 24) }}</p>
                        @if($m->estEnRetard())
                            <p class="text-xs text-red-500 mt-0.5"><i class="fa-solid fa-triangle-exclamation mr-1"></i>En retard</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-600 capitalize hidden sm:table-cell">{{ $m->type }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $m->statut_badge }}">{{ $m->statut_label }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-600 text-xs">{{ $m->date_planifiee?->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-slate-600 hidden md:table-cell">{{ $m->technicien ?? '—' }}</td>
                    <td class="px-4 py-3 text-right text-slate-600 hidden lg:table-cell">
                        {{ $m->cout ? number_format($m->cout, 0, ',', ' ') . ' F' : '—' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($m->statut !== 'termine')
                        <a href="{{ route('gestionnaire.maintenances.edit', $m) }}"
                           class="bg-amber-100 hover:bg-amber-200 text-amber-700 px-2 py-1 rounded text-xs font-medium transition-colors">
                            <i class="fa-solid fa-pen"></i>
                            <span class="hidden sm:inline ml-1">Modifier</span>
                        </a>
                        @else
                        <span class="text-slate-300 text-xs">Clôturée</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-screwdriver-wrench text-4xl mb-3 block"></i>
                        <p class="font-medium">Aucune maintenance</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 lg:px-6 py-4 border-t border-slate-100">{{ $maintenances->links() }}</div>
</div>

@endsection