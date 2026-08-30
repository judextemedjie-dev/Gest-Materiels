{{-- resources/views/gestionnaire/signalements/index.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Signalements')
@section('page_title', 'Signalements clients')
@section('page_subtitle', 'Problèmes reportés par vos clients')

@section('content')

@if($nouveaux > 0)
<div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5 flex items-center gap-3">
    <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl flex-shrink-0"></i>
    <div>
        <p class="text-sm font-semibold text-red-800">{{ $nouveaux }} nouveau(x) signalement(s) non lu(s)</p>
        <p class="text-xs text-red-600 mt-0.5">Des clients ont signalé des problèmes sur leurs matériels.</p>
    </div>
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200">
        <h3 class="text-sm font-semibold text-slate-700">
            <i class="fa-solid fa-bell text-amber-500 mr-2"></i>
            Tous les signalements ({{ $signalements->total() }})
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" style="min-width:600px">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Client</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Matériel</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Type</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase hidden md:table-cell">Description</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Statut</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase hidden sm:table-cell">Date</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($signalements as $sig)
                <tr class="hover:bg-slate-50 {{ $sig->statut === 'nouveau' ? 'bg-red-50/30' : '' }}">
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-800">{{ $sig->client?->nom }}</p>
                        <p class="text-xs text-slate-400">{{ $sig->client?->contact }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-700">{{ Str::limit($sig->materiel?->designation, 22) }}</p>
                        <p class="text-xs font-mono text-slate-400">{{ $sig->materiel?->code_identification }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm">{{ $sig->type_label }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-600 text-xs hidden md:table-cell">
                        {{ Str::limit($sig->description, 50) }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $sig->statut_badge }}">
                            {{ $sig->statut_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-500 text-xs hidden sm:table-cell">
                        {{ $sig->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            @if($sig->statut === 'nouveau')
                            <form method="POST" action="{{ route('gestionnaire.signalements.lu', $sig) }}">
                                @csrf
                                <button type="submit"
                                        class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-2 py-1 rounded text-xs font-medium transition-colors"
                                        title="Marquer lu">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </form>
                            @endif
                            @if($sig->statut !== 'traite')
                            <form method="POST" action="{{ route('gestionnaire.signalements.traite', $sig) }}">
                                @csrf
                                <button type="submit"
                                        class="bg-green-100 hover:bg-green-200 text-green-700 px-2 py-1 rounded text-xs font-medium transition-colors"
                                        title="Marquer traité">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-bell-slash text-4xl mb-3 block"></i>
                        <p class="font-medium">Aucun signalement</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $signalements->links() }}</div>
</div>

@endsection