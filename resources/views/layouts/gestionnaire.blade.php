{{-- resources/views/layouts/gestionnaire.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestionnaire') — GestMatériel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar { background-color: #1e293b; }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 14px; border-radius: 8px;
            color: #cbd5e1; font-size: 13.5px; font-weight: 500;
            text-decoration: none; transition: all 0.15s;
        }
        .sidebar-link:hover { background-color: #334155; color: #fff; }
        .sidebar-link.active { background-color: #0d9488; color: #fff; }
        .sidebar-link i { width: 16px; text-align: center; font-size: 13px; }
        .sidebar-section {
            font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em;
            color: #64748b; padding: 0 14px; margin: 16px 0 6px; font-weight: 600;
        }
        @media (max-width: 1023px) {
            .sidebar-drawer {
                position: fixed; top: 0; left: 0; height: 100vh;
                width: 260px; z-index: 50;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
            }
            .sidebar-drawer.open { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans" x-data="{ sidebarOpen: false }">

<!-- Overlay mobile -->
<div class="fixed inset-0 bg-black/50 z-40 lg:hidden"
     x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak></div>

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar sidebar-drawer lg:relative lg:translate-x-0 lg:flex lg:flex-col w-64 flex-shrink-0"
           :class="sidebarOpen ? 'open flex flex-col' : ''"
           style="min-width:256px">

        <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-700">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#0d9488,#0f766e)">
                <i class="fa-solid fa-warehouse text-white text-sm"></i>
            </div>
            <div class="flex-1">
                <p class="font-bold text-sm text-white leading-tight">GestMatériel</p>
                <p class="text-xs text-slate-400 mt-0.5">Gestionnaire</p>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <nav class="flex-1 px-3 py-3 overflow-y-auto">
            <p class="sidebar-section">Tableau de bord</p>
            <a href="{{ route('gestionnaire.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('gestionnaire.dashboard') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <i class="fa-solid fa-gauge"></i> Vue d'ensemble
            </a>
            <p class="sidebar-section">Gestion</p>
            <a href="{{ route('gestionnaire.materiels.index') }}"
               class="sidebar-link {{ request()->routeIs('gestionnaire.materiels.*') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <i class="fa-solid fa-box"></i> Matériels
            </a>
            <a href="{{ route('gestionnaire.clients.index') }}"
               class="sidebar-link {{ request()->routeIs('gestionnaire.clients.*') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <i class="fa-solid fa-building-user"></i> Clients
            </a>
            <a href="{{ route('gestionnaire.affectations.index') }}"
               class="sidebar-link {{ request()->routeIs('gestionnaire.affectations.*') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <i class="fa-solid fa-right-left"></i> Affectations
            </a>
            <a href="{{ route('gestionnaire.maintenances.index') }}"
               class="sidebar-link {{ request()->routeIs('gestionnaire.maintenances.*') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <i class="fa-solid fa-screwdriver-wrench"></i> Maintenance
            </a>
            <p class="sidebar-section">Exports</p>
            <a href="{{ route('gestionnaire.rapports.index') }}"
               class="sidebar-link {{ request()->routeIs('gestionnaire.rapports.*') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <i class="fa-solid fa-chart-bar"></i> Rapports
            </a>
        </nav>

        {{-- User Info --}}
        <div class="px-4 py-4 border-t border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                     style="background:#0d9488">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-slate-400 text-xs">Gestionnaire</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Déconnexion" class="text-slate-400 hover:text-red-400 transition-colors">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- Topbar --}}
        <header class="bg-white border-b border-slate-200 px-4 lg:px-6 py-3 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true"
                        class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-bars text-slate-600"></i>
                </button>
                <div>
                    <h1 class="text-base lg:text-lg font-semibold text-slate-800 leading-tight">
                        @yield('page_title', 'Tableau de bord')
                    </h1>
                    <p class="text-xs text-slate-500 hidden sm:block">@yield('page_subtitle', '')</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @php $nbNotifs = Auth::user()->unreadNotifications->count(); @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="relative w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-bell text-slate-600 text-sm"></i>
                        @if($nbNotifs > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                            {{ $nbNotifs > 9 ? '9+' : $nbNotifs }}
                        </span>
                        @endif
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 top-11 w-72 sm:w-80 bg-white rounded-xl shadow-2xl border border-slate-200 z-50">
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                            <p class="text-sm font-semibold text-slate-700">Notifications</p>
                            @if($nbNotifs > 0)
                            <form method="POST" action="{{ route('gestionnaire.notifications.readAll') }}">
                                @csrf
                                <button type="submit" class="text-xs text-teal-600 hover:underline">Tout lire</button>
                            </form>
                            @endif
                        </div>
                        <div class="max-h-64 overflow-y-auto divide-y divide-slate-50">
                            @forelse(Auth::user()->notifications->take(6) as $notif)
                            <div class="px-4 py-3 {{ $notif->read_at ? 'opacity-60' : '' }}">
                                <p class="text-xs font-semibold text-slate-700">{{ $notif->data['titre'] ?? '' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $notif->data['message'] ?? '' }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            @empty
                            <div class="px-4 py-6 text-center text-slate-400 text-sm">Aucune notification</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <a href="{{ route('gestionnaire.affectations.create') }}"
                   class="hidden sm:flex items-center gap-2 text-xs font-medium text-white px-3 py-2 rounded-lg transition-colors"
                   style="background:#0d9488">
                    <i class="fa-solid fa-plus"></i>
                    <span class="hidden md:inline">Nouvelle affectation</span>
                    <span class="md:hidden">Affecter</span>
                </a>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-4 lg:px-6 pt-3">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-2 text-sm">
                    <i class="fa-solid fa-circle-check text-green-500 flex-shrink-0"></i>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button @click="show=false"><i class="fa-solid fa-xmark text-green-400"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show"
                     class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-2 text-sm">
                    <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button @click="show=false"><i class="fa-solid fa-xmark text-red-400"></i></button>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-2">
                    <p class="text-sm font-semibold mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Erreurs :</p>
                    <ul class="text-xs list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Contenu principal --}}
        <main class="flex-1 overflow-y-auto px-4 lg:px-6 py-4">
            @yield('content')
        </main>

        {{-- ===== FOOTER ===== --}}
        <footer class="bg-white border-t border-slate-200 px-4 lg:px-6 py-3 flex-shrink-0">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0"
                         style="background:linear-gradient(135deg,#0d9488,#0f766e)">
                        <i class="fa-solid fa-warehouse text-white" style="font-size:8px"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-600">GestMatériel</span>
                    <span class="text-xs text-slate-300">|</span>
                    <span class="text-xs text-slate-400">v1.0.0</span>
                </div>
                <div class="text-xs text-slate-400 text-center">
                    &copy; {{ date('Y') }}
                    <span class="font-medium text-slate-500">Materiel_Institut</span>
                    — Tous droits réservés
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
                        <span class="text-xs text-slate-400">Système opérationnel</span>
                    </div>
                    <span class="text-xs text-slate-300 hidden sm:inline">|</span>
                    <span class="text-xs text-slate-400 hidden sm:inline">
                        <i class="fa-solid fa-clock mr-1 text-slate-300"></i>{{ now()->format('H:i') }}
                    </span>
                </div>
            </div>
        </footer>

    </div>
</div>

@stack('scripts')
</body>
</html>