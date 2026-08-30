{{-- resources/views/portail/index.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon espace — GestMatériel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        body { background: #f8fafc; }
        .card { background: white; border-radius: 16px; box-shadow: 0 1px 6px rgba(0,0,0,0.07); }
    </style>
</head>
<body class="min-h-screen pb-10">

    {{-- HEADER --}}
    <div style="background:linear-gradient(135deg,#1e3a5f,#2563eb)" class="px-5 pt-10 pb-16">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-boxes-stacked text-white"></i>
            </div>
            <div>
                <p class="text-white/70 text-xs">GestMatériel</p>
                <p class="text-white font-bold text-sm">Mon espace client</p>
            </div>
        </div>
        <h1 class="text-white text-2xl font-bold">Bonjour,</h1>
        <h2 class="text-white text-2xl font-bold">{{ $client->nom }} 👋</h2>
        <p class="text-white/60 text-sm mt-1">{{ $client->email }} · {{ $client->contact }}</p>
    </div>

    <div class="px-4 -mt-8 space-y-4">

        {{-- Flash success --}}
        @if(session('success'))
        <div class="card p-4 flex items-center gap-3 border-l-4 border-green-500">
            <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        {{-- MATÉRIELS EN COURS --}}
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-box text-blue-500"></i>
                    Matériels en votre possession
                </h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">
                    {{ $client->affectationsEnCours->count() }}
                </span>
            </div>

            @forelse($client->affectationsEnCours as $aff)
            <div class="border border-slate-100 rounded-xl p-4 mb-3 last:mb-0">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">{{ $aff->materiel?->designation }}</p>
                        <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $aff->materiel?->code_identification }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-xs text-slate-500">
                                <i class="fa-regular fa-calendar mr-1 text-blue-400"></i>
                                Assigné le {{ $aff->date_affectation?->format('d/m/Y') }}
                            </span>
                            <span class="text-xs text-slate-500">
                                <i class="fa-solid fa-layer-group mr-1 text-purple-400"></i>
                                Qté : {{ $aff->quantite }}
                            </span>
                        </div>
                    </div>
                    <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-full flex-shrink-0">
                        En cours
                    </span>
                </div>

                {{-- Bouton signaler un problème --}}
                <a href="{{ route('portail.signaler.form', [$token, $aff->id]) }}"
                   class="mt-3 w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold transition-colors"
                   style="background:#fee2e2;color:#b91c1c">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Signaler un problème
                </a>
            </div>
            @empty
            <div class="text-center py-8">
                <i class="fa-solid fa-box-open text-4xl text-slate-200 mb-3 block"></i>
                <p class="text-slate-400 text-sm">Aucun matériel actuellement en votre possession</p>
            </div>
            @endforelse
        </div>

        {{-- MES SIGNALEMENTS --}}
        @if($client->signalements->isNotEmpty())
        <div class="card p-5">
            <h3 class="font-bold text-slate-800 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-bell text-amber-500"></i>
                Mes signalements
            </h3>
            <div class="space-y-3">
                @foreach($client->signalements->take(5) as $sig)
                <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5
                        {{ $sig->statut === 'traite' ? 'bg-green-100' : ($sig->statut === 'lu' ? 'bg-yellow-100' : 'bg-red-100') }}">
                        <i class="fa-solid fa-triangle-exclamation text-xs
                            {{ $sig->statut === 'traite' ? 'text-green-600' : ($sig->statut === 'lu' ? 'text-yellow-600' : 'text-red-600') }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-700">{{ $sig->materiel?->designation }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $sig->type_label }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit($sig->description, 60) }}</p>
                        <p class="text-xs text-slate-300 mt-1">{{ $sig->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium flex-shrink-0 {{ $sig->statut_badge }}">
                        {{ $sig->statut_label }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- HISTORIQUE --}}
        <div class="card p-5">
            <h3 class="font-bold text-slate-800 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                Historique des affectations
            </h3>
            <div class="space-y-2">
                @forelse($client->affectations as $aff)
                <div class="flex items-center justify-between py-2.5 border-b border-slate-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-slate-700">{{ Str::limit($aff->materiel?->designation, 28) }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            {{ $aff->date_affectation?->format('d/m/Y') }}
                            @if($aff->date_restitution)
                                → {{ $aff->date_restitution->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $aff->statut_badge }}">
                        {{ $aff->statut_label }}
                    </span>
                </div>
                @empty
                <p class="text-slate-400 text-sm text-center py-4">Aucun historique</p>
                @endforelse
            </div>
        </div>

        {{-- INFO --}}
        <div class="card p-4 border-l-4 border-blue-400">
            <p class="text-xs text-slate-500">
                <i class="fa-solid fa-circle-info text-blue-400 mr-1"></i>
                Pour restituer un matériel, rapportez-le physiquement à notre service.
                Le gestionnaire enregistrera la restitution dans le système.
            </p>
        </div>

        {{-- FOOTER --}}
        <div class="text-center pt-2">
            <p class="text-xs text-slate-400">GestMatériel &copy; {{ date('Y') }} — Materiel_Institut</p>
            <p class="text-xs text-slate-300 mt-1">Lien sécurisé et personnel — Ne pas partager</p>
        </div>
    </div>

</body>
</html>