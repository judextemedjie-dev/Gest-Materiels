{{-- resources/views/gestionnaire/maintenances/index.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Maintenances')
@section('page_title', 'Maintenance')
@section('page_subtitle', 'Suivi des interventions et réparations')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200 flex flex-wrap gap-3 items-center justify-between">
        <form method="GET" action="{{ route('gestionnaire.maintenances.index') }}" class="flex flex-wrap gap-2 items-center">
            <select name="statut" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Tous statuts</option>
                <option value="planifie"  {{ request('statut') == 'planifie' ? 'selected' : '' }}>Planifié</option>
                <option value="en_cours"  {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="termine"   {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
            </select>
            <select name="type" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Tous types</option>
                <option value="controle"     {{ request('type') == 'controle' ? 'selected' : '' }}>Contrôle</option>
                <option value="reparation"   {{ request('type') == 'reparation' ? 'selected' : '' }}>Réparation</option>
                <option value="intervention" {{ request('type') == 'intervention' ? 'selected' : '' }}>Intervention</option>
            </select>
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filtrer
            </button>
        </form>
        <a href="{{ route('gestionnaire.maintenances.create') }}"
           class="bg-teal-600 hover:bg-teal-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-plus"></i> Planifier une maintenance
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Matériel</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Type</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Statut</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date planifiée</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Technicien</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Coût</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($maintenances as $m)
                <tr class="hover:bg-slate-50 transition-colors {{ $m->estEnRetard() ? 'bg-red-50/30' : '' }}">
                    <td class="px-4 py-3 font-medium text-slate-800">
                        {{ Str::limit($m->materiel?->designation, 28) }}
                        @if($m->estEnRetard())
                            <span class="ml-1 text-red-500 text-xs"><i class="fa-solid fa-triangle-exclamation"></i> En retard</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-600 capitalize">{{ $m->type }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $m->statut_badge }}">{{ $m->statut_label }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $m->date_planifiee?->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $m->technicien ?? '—' }}</td>
                    <td class="px-4 py-3 text-right text-slate-600">
                        {{ $m->cout ? number_format($m->cout, 0, ',', ' ') . ' FCFA' : '—' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($m->statut !== 'termine')
                        <a href="{{ route('gestionnaire.maintenances.edit', $m) }}"
                           class="bg-amber-100 hover:bg-amber-200 text-amber-700 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                            <i class="fa-solid fa-pen mr-1"></i> Mettre à jour
                        </a>
                        @else
                        <span class="text-slate-300 text-xs">Clôturée</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-screwdriver-wrench text-4xl mb-3"></i>
                        <p class="font-medium">Aucune maintenance planifiée</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $maintenances->links() }}</div>
</div>
@endsection