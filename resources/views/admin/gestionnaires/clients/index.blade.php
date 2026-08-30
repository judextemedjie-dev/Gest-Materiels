{{-- resources/views/gestionnaire/clients/index.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Clients')
@section('page_title', 'Gestion des clients')
@section('page_subtitle', 'Liste des clients de votre portefeuille')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('gestionnaire.clients.index') }}" class="flex gap-2 items-center">
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, contact..."
                       class="pl-8 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 w-56">
            </div>
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filtrer
            </button>
        </form>
        <a href="{{ route('gestionnaire.clients.create') }}"
           class="bg-teal-600 hover:bg-teal-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-plus"></i> Créer un client
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nom</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Matériels assignés</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($clients as $client)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-slate-400">#{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-teal-100 rounded-full flex items-center justify-center text-teal-600 font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr($client->nom, 0, 2)) }}
                            </div>
                            {{ $client->nom }}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $client->email }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $client->contact }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($client->en_cours > 0)
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ $client->en_cours }} assigné(s)
                            </span>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('gestionnaire.clients.show', $client) }}"
                               class="text-blue-500 hover:text-blue-700 transition-colors" title="Voir la fiche">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('gestionnaire.affectations.create') }}?client_id={{ $client->id }}"
                               class="text-teal-500 hover:text-teal-700 transition-colors" title="Assigner matériel">
                                <i class="fa-solid fa-right-left"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-building-user text-4xl mb-3"></i>
                        <p class="font-medium">Aucun client enregistré</p>
                        <a href="{{ route('gestionnaire.clients.create') }}" class="text-teal-600 hover:underline text-sm mt-1 inline-block">Créer le premier client</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $clients->links() }}</div>
</div>
@endsection