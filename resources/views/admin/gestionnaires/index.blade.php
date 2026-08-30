{{-- resources/views/admin/gestionnaires/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Gestionnaires')
@section('page_title', 'Gestion des gestionnaires')
@section('page_subtitle', 'Créer et gérer les comptes gestionnaires')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-sm font-semibold text-slate-700">
            <i class="fa-solid fa-users-gear text-blue-500 mr-2"></i>
            Liste des gestionnaires ({{ $gestionnaires->total() }})
        </h3>
        <button onclick="document.getElementById('modalGestionnaire').classList.remove('hidden')"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-plus"></i> Nouveau gestionnaire
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Gestionnaire</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Clients</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Affectations</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Créé le</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($gestionnaires as $g)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xs">
                                {{ strtoupper(substr($g->name, 0, 2)) }}
                            </div>
                            <span class="font-medium text-slate-800">{{ $g->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $g->email }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2.5 py-1 rounded-full">{{ $g->clients_count }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">{{ $g->affectations_count }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-500 text-xs">{{ $g->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('admin.gestionnaires.destroy', $g) }}"
                              onsubmit="return confirm('Supprimer {{ $g->name }} ? Cette action est irréversible.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="Supprimer">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-user-slash text-3xl mb-3"></i>
                        <p>Aucun gestionnaire créé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $gestionnaires->links() }}</div>
</div>

{{-- Modal --}}
<div id="modalGestionnaire" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
            <h3 class="text-base font-semibold text-slate-800"><i class="fa-solid fa-user-plus text-blue-500 mr-2"></i>Créer un gestionnaire</h3>
            <button onclick="document.getElementById('modalGestionnaire').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.gestionnaires.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: Marie Ekotto"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="gestionnaire@institution.cm"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Mot de passe</label>
                <input type="password" name="password" required placeholder="Minimum 8 caractères"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Confirmer mot de passe</label>
                <input type="password" name="password_confirmation" required placeholder="Répétez le mot de passe"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modalGestionnaire').classList.add('hidden')"
                        class="flex-1 border border-slate-300 text-slate-700 py-2.5 rounded-lg text-sm hover:bg-slate-50">Annuler</button>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-user-plus mr-1"></i> Créer
                </button>
            </div>
        </form>
    </div>
</div>
@if($errors->any())
<script>document.getElementById('modalGestionnaire').classList.remove('hidden');</script>
@endif
@endsection