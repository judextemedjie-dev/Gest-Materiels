{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page_title', 'Tableau de bord')
@section('page_subtitle', 'Vue globale — ' . now()->format('d/m/Y'))

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-5 mb-5">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-users-gear text-blue-600 text-lg"></i>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $stats['gestionnaires'] }}</p>
            <p class="text-xs text-slate-500">Gestionnaires</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-building-user text-teal-600 text-lg"></i>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $stats['clients'] }}</p>
            <p class="text-xs text-slate-500">Clients</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-boxes-stacked text-violet-600 text-lg"></i>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $stats['materiels'] }}</p>
            <p class="text-xs text-slate-500">Matériels</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-right-left text-amber-600 text-lg"></i>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $stats['affectations_cours'] }}</p>
            <p class="text-xs text-slate-500">En cours</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 lg:p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-chart-pie text-blue-500 mr-2"></i>État du parc
        </h3>
        <canvas id="statutChart" height="220"></canvas>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-4 lg:p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-2"></i>Stock critique
        </h3>
        @if($stockCritique->isEmpty())
            <div class="text-center py-8 text-slate-400">
                <i class="fa-solid fa-circle-check text-3xl text-green-400 mb-2 block"></i>
                <p class="text-sm">Aucun stock critique</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($stockCritique as $m)
                <div class="flex items-center justify-between py-2 px-3 rounded-lg
                    {{ $m->quantite_stock == 0 ? 'bg-red-50 border border-red-200' : 'bg-amber-50 border border-amber-200' }}">
                    <div>
                        <p class="text-sm font-medium text-slate-700">{{ $m->designation }}</p>
                        <p class="text-xs text-slate-500">{{ $m->code_identification }}</p>
                    </div>
                    <span class="text-lg font-bold {{ $m->quantite_stock == 0 ? 'text-red-600' : 'text-amber-600' }}">
                        {{ $m->quantite_stock }}
                    </span>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    {{-- Activité récente --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 lg:p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-clock-rotate-left text-slate-400 mr-2"></i>Activité récente
        </h3>
        <div class="overflow-x-auto -mx-4 px-4">
            <table class="w-full text-xs" style="min-width:400px">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left py-2 text-slate-500 font-medium">Gestionnaire</th>
                        <th class="text-left py-2 text-slate-500 font-medium">Action</th>
                        <th class="text-left py-2 text-slate-500 font-medium">Matériel</th>
                        <th class="text-left py-2 text-slate-500 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($activiteRecente as $mvt)
                    <tr class="hover:bg-slate-50">
                        <td class="py-2 font-medium text-slate-700">{{ Str::limit($mvt->user?->name, 14) }}</td>
                        <td class="py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                {{ $mvt->type === 'affectation' ? 'bg-blue-100 text-blue-700' :
                                   ($mvt->type === 'retour' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $mvt->type_label }}
                            </span>
                        </td>
                        <td class="py-2 text-slate-600">{{ Str::limit($mvt->materiel?->designation, 18) }}</td>
                        <td class="py-2 text-slate-400">{{ $mvt->created_at->format('d/m H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-6 text-center text-slate-400">Aucune activité</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Gestionnaires --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 lg:p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-700">
                <i class="fa-solid fa-users-gear text-blue-500 mr-2"></i>Gestionnaires
            </h3>
            <button onclick="document.getElementById('modalGestionnaire').classList.remove('hidden')"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-plus"></i> <span class="hidden sm:inline">Créer</span>
            </button>
        </div>
        <div class="space-y-2">
            @forelse($gestionnaires as $g)
            <div class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:border-slate-200 transition-colors">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xs flex-shrink-0">
                    {{ strtoupper(substr($g->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-700 truncate">{{ $g->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ $g->email }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-slate-600"><span class="font-semibold">{{ $g->affectations_count }}</span></p>
                    <p class="text-xs text-slate-400">affect.</p>
                </div>
                <form method="POST" action="{{ route('admin.gestionnaires.destroy', $g) }}"
                      onsubmit="return confirm('Supprimer ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-slate-300 hover:text-red-500 ml-1">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
            @empty
            <div class="text-center py-8 text-slate-400">
                <i class="fa-solid fa-user-slash text-2xl mb-2 block"></i>
                <p class="text-sm">Aucun gestionnaire</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Modal Gestionnaire --}}
<div id="modalGestionnaire" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 sticky top-0 bg-white">
            <h3 class="text-base font-semibold text-slate-800">
                <i class="fa-solid fa-user-plus text-blue-500 mr-2"></i>Créer un gestionnaire
            </h3>
            <button onclick="document.getElementById('modalGestionnaire').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 text-xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.gestionnaires.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: Jean-Pierre Mbarga"
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
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-medium">
                    <i class="fa-solid fa-user-plus mr-1"></i> Créer
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const statutData = @json($statutStats);
const labels = { en_service:'En service', en_panne:'En panne', en_reparation:'En réparation', archive:'Archivé' };
const colors  = { en_service:'#16a34a', en_panne:'#dc2626', en_reparation:'#d97706', archive:'#94a3b8' };
new Chart(document.getElementById('statutChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statutData).map(k => labels[k] || k),
        datasets: [{ data: Object.values(statutData),
            backgroundColor: Object.keys(statutData).map(k => colors[k] || '#94a3b8'),
            borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } } } }
});
@if($errors->any()) document.getElementById('modalGestionnaire').classList.remove('hidden'); @endif
</script>
@endpush