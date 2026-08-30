
@extends('layouts.admin')
@section('title', 'Statistiques globales')
@section('page_title', 'Statistiques globales')
@section('page_subtitle', 'Analyse complète du parc matériel — ' . now()->format('d/m/Y'))

@section('content')

{{-- ===== CARTES CHIFFRES CLÉS ===== --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff">
            <i class="fa-solid fa-boxes-stacked text-lg" style="color:#2563eb"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['total_materiels'] }}</p>
            <p class="text-xs text-slate-500">Matériels total</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#f0fdf4">
            <i class="fa-solid fa-layer-group text-lg" style="color:#16a34a"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['total_stock'] }}</p>
            <p class="text-xs text-slate-500">Unités en stock</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fefce8">
            <i class="fa-solid fa-right-left text-lg" style="color:#ca8a04"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['affectations_cours'] }}</p>
            <p class="text-xs text-slate-500">Affectations en cours</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fdf2f8">
            <i class="fa-solid fa-percent text-lg" style="color:#9333ea"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['taux_restitution'] }}%</p>
            <p class="text-xs text-slate-500">Taux de restitution</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#f0fdfa">
            <i class="fa-solid fa-building-user text-lg" style="color:#0d9488"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['clients'] }}</p>
            <p class="text-xs text-slate-500">Clients enregistrés</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff">
            <i class="fa-solid fa-users-gear text-lg" style="color:#2563eb"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['gestionnaires'] }}</p>
            <p class="text-xs text-slate-500">Gestionnaires actifs</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fff7ed">
            <i class="fa-solid fa-screwdriver-wrench text-lg" style="color:#ea580c"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['maintenances_actives'] }}</p>
            <p class="text-xs text-slate-500">Maintenances actives</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fef2f2">
            <i class="fa-solid fa-clock text-lg" style="color:#dc2626"></i>
        </div>
        <div>
            <p class="text-2xl font-bold {{ $stats['maintenances_retard'] > 0 ? 'text-red-600' : 'text-slate-800' }}">
                {{ $stats['maintenances_retard'] }}
            </p>
            <p class="text-xs text-slate-500">Maintenances en retard</p>
        </div>
    </div>

</div>

{{-- ===== GRAPHIQUES LIGNE 1 ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

    {{-- Évolution affectations 12 mois --}}
    <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-chart-line mr-2" style="color:#2563eb"></i>
            Évolution des affectations (12 derniers mois)
        </h3>
        <canvas id="chartAffectations" height="120"></canvas>
    </div>

    {{-- Répartition statuts --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-chart-pie mr-2" style="color:#9333ea"></i>
            État du parc matériel
        </h3>
        <canvas id="chartStatuts" height="200"></canvas>
    </div>

</div>

{{-- ===== GRAPHIQUES LIGNE 2 ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-5">

    {{-- Stock par catégorie --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-chart-bar mr-2" style="color:#0d9488"></i>
            Stock disponible par catégorie
        </h3>
        <canvas id="chartStock" height="160"></canvas>
    </div>

    {{-- Top 5 matériels affectés --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-trophy mr-2" style="color:#ca8a04"></i>
            Top 5 matériels les plus affectés
        </h3>
        @if($topMateriels->isEmpty())
            <p class="text-slate-400 text-sm text-center py-8">Aucune donnée</p>
        @else
        <div class="space-y-3">
            @foreach($topMateriels as $i => $m)
            @php
                $pct = $topMateriels->first()->affectations_count > 0
                    ? round(($m->affectations_count / $topMateriels->first()->affectations_count) * 100)
                    : 0;
                $colors = ['#2563eb','#0d9488','#ca8a04','#9333ea','#dc2626'];
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full text-white text-xs flex items-center justify-center font-bold flex-shrink-0"
                              style="background:{{ $colors[$i] }}">{{ $i+1 }}</span>
                        <span class="text-xs font-medium text-slate-700">{{ Str::limit($m->designation, 30) }}</span>
                    </div>
                    <span class="text-xs font-bold text-slate-600">{{ $m->affectations_count }}</span>
                </div>
                <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all" style="width:{{ $pct }}%;background:{{ $colors[$i] }}"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- ===== TABLEAU LIGNE 3 ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-5">

    {{-- Performance gestionnaires --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-ranking-star mr-2" style="color:#2563eb"></i>
            Performance des gestionnaires
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left py-2 text-slate-500 font-medium">Gestionnaire</th>
                        <th class="text-center py-2 text-slate-500 font-medium">Clients</th>
                        <th class="text-center py-2 text-slate-500 font-medium">Affectations</th>
                        <th class="text-left py-2 text-slate-500 font-medium">Activité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($topGestionnaires as $g)
                    @php
                        $max = $topGestionnaires->max('affectations_count');
                        $pct = $max > 0 ? round(($g->affectations_count / $max) * 100) : 0;
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="py-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                     style="background:#2563eb">
                                    {{ strtoupper(substr($g->name, 0, 2)) }}
                                </div>
                                <span class="font-medium text-slate-700">{{ $g->name }}</span>
                            </div>
                        </td>
                        <td class="py-2.5 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold" style="background:#f0fdfa;color:#0d9488">
                                {{ $g->clients_count }}
                            </span>
                        </td>
                        <td class="py-2.5 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold" style="background:#eff6ff;color:#2563eb">
                                {{ $g->affectations_count }}
                            </span>
                        </td>
                        <td class="py-2.5" style="min-width:80px">
                            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full" style="width:{{ $pct }}%;background:#2563eb"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-6 text-center text-slate-400">Aucun gestionnaire</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Stock critique --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-triangle-exclamation mr-2" style="color:#ca8a04"></i>
            Stock critique
            @if($stockCritique->count() > 0)
                <span class="ml-1 text-xs px-2 py-0.5 rounded-full font-semibold" style="background:#fef2f2;color:#dc2626">
                    {{ $stockCritique->count() }} alertes
                </span>
            @endif
        </h3>
        @if($stockCritique->isEmpty())
            <div class="text-center py-8">
                <i class="fa-solid fa-circle-check text-3xl mb-2" style="color:#16a34a"></i>
                <p class="text-sm text-slate-400">Aucun stock critique — tout est OK !</p>
            </div>
        @else
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($stockCritique as $m)
                <div class="flex items-center justify-between p-3 rounded-lg border
                    {{ $m->quantite_stock == 0 ? 'border-red-200 bg-red-50' : 'border-amber-200 bg-amber-50' }}">
                    <div>
                        <p class="text-xs font-semibold text-slate-700">{{ $m->designation }}</p>
                        <p class="text-xs text-slate-500">{{ $m->code_identification }} · {{ $m->localisation ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right flex-shrink-0 ml-3">
                        <p class="text-xl font-bold {{ $m->quantite_stock == 0 ? 'text-red-600' : 'text-amber-600' }}">
                            {{ $m->quantite_stock }}
                        </p>
                        <p class="text-xs text-slate-400">unité(s)</p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

{{-- ===== DERNIÈRES OPÉRATIONS ===== --}}
<div class="bg-white rounded-xl border border-slate-200 p-5">
    <h3 class="text-sm font-semibold text-slate-700 mb-4">
        <i class="fa-solid fa-clock-rotate-left mr-2 text-slate-400"></i>
        Dernières opérations — tous gestionnaires
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left py-2 text-slate-500 font-medium">Gestionnaire</th>
                    <th class="text-left py-2 text-slate-500 font-medium">Type</th>
                    <th class="text-left py-2 text-slate-500 font-medium">Matériel</th>
                    <th class="text-left py-2 text-slate-500 font-medium">Client</th>
                    <th class="text-center py-2 text-slate-500 font-medium">Qté</th>
                    <th class="text-left py-2 text-slate-500 font-medium">Date & Heure</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($dernieresOperations as $op)
                <tr class="hover:bg-slate-50">
                    <td class="py-2 font-medium text-slate-700">{{ $op->user?->name ?? 'N/A' }}</td>
                    <td class="py-2">
                        @php
                            $typeColors = [
                                'affectation' => 'background:#eff6ff;color:#2563eb',
                                'retour'      => 'background:#f0fdf4;color:#16a34a',
                                'ajout'       => 'background:#f0fdfa;color:#0d9488',
                                'archivage'   => 'background:#f1f5f9;color:#64748b',
                            ];
                            $color = $typeColors[$op->type] ?? 'background:#f1f5f9;color:#64748b';
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium" style="{{ $color }}">
                            {{ $op->type_label }}
                        </span>
                    </td>
                    <td class="py-2 text-slate-600">{{ Str::limit($op->materiel?->designation, 22) ?? '—' }}</td>
                    <td class="py-2 text-slate-600">{{ $op->client?->nom ?? '—' }}</td>
                    <td class="py-2 text-center font-semibold text-slate-700">{{ $op->quantite }}</td>
                    <td class="py-2 text-slate-400">{{ $op->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-6 text-center text-slate-400">Aucune opération enregistrée</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ---- Données PHP → JS
const labelsParMois     = @json($labelsParMois);
const affectationsData  = @json($affectationsParMois);
const statutData        = @json($statutStats);
const stockCategories   = @json($stockParCategorie);

const statutLabels = { en_service:'En service', en_panne:'En panne', en_reparation:'En réparation', archive:'Archivé' };
const statutColors = { en_service:'#16a34a', en_panne:'#dc2626', en_reparation:'#ea580c', archive:'#94a3b8' };

// ---- Graphique 1 : Évolution affectations
new Chart(document.getElementById('chartAffectations').getContext('2d'), {
    type: 'line',
    data: {
        labels: labelsParMois,
        datasets: [{
            label: 'Affectations',
            data: affectationsData,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#2563eb',
            pointRadius: 4,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f1f5f9' } },
            x: { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
    }
});

// ---- Graphique 2 : Statuts (donut)
new Chart(document.getElementById('chartStatuts').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statutData).map(k => statutLabels[k] || k),
        datasets: [{
            data: Object.values(statutData),
            backgroundColor: Object.keys(statutData).map(k => statutColors[k] || '#94a3b8'),
            borderWidth: 2, borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } }
        }
    }
});

// ---- Graphique 3 : Stock par catégorie (barres horizontales)
new Chart(document.getElementById('chartStock').getContext('2d'), {
    type: 'bar',
    data: {
        labels: stockCategories.map(c => c.nom),
        datasets: [{
            label: 'Unités en stock',
            data: stockCategories.map(c => c.stock),
            backgroundColor: ['#2563eb','#0d9488','#ca8a04','#9333ea','#dc2626','#16a34a'],
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { font: { size: 11 } }, grid: { color: '#f1f5f9' } },
            y: { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
    }
});
</script>
@endpush