{{-- resources/views/gestionnaire/clients/index.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Clients')
@section('page_title', 'Clients')
@section('page_subtitle', 'Gestion des clients')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-4 lg:px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row gap-3">
        <form method="GET" action="{{ route('gestionnaire.clients.index') }}" class="flex gap-2 items-center flex-1">
            <div class="relative flex-1 sm:flex-none">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, contact..."
                       class="pl-8 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 w-full sm:w-52">
            </div>
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded-lg text-sm">
                <i class="fa-solid fa-filter"></i>
            </button>
        </form>
        <a href="{{ route('gestionnaire.clients.create') }}"
           class="bg-teal-600 hover:bg-teal-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors self-start sm:self-auto whitespace-nowrap">
            <i class="fa-solid fa-plus"></i> Créer un client
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" style="min-width:500px">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">ID</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Nom</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase hidden sm:table-cell">Email</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase hidden md:table-cell">Contact</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Assignés</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Actions</th>
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
                            <span class="truncate max-w-[120px]">{{ $client->nom }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-600 text-xs hidden sm:table-cell">{{ $client->email }}</td>
                    <td class="px-4 py-3 text-slate-600 hidden md:table-cell">{{ $client->contact }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($client->en_cours > 0)
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-full">
                                {{ $client->en_cours }}
                            </span>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('gestionnaire.clients.show', $client) }}"
                               class="text-blue-500 hover:text-blue-700" title="Voir">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('gestionnaire.affectations.create') }}?client_id={{ $client->id }}"
                               class="text-teal-500 hover:text-teal-700" title="Assigner">
                                <i class="fa-solid fa-right-left"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-building-user text-4xl mb-3 block"></i>
                        <p class="font-medium">Aucun client</p>
                        <a href="{{ route('gestionnaire.clients.create') }}" class="text-teal-600 hover:underline text-sm mt-1 inline-block">
                            Créer le premier client
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 lg:px-6 py-4 border-t border-slate-100">{{ $clients->links() }}</div>
</div>

@endsection