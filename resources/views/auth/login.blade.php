<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — GestMatériel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            min-height: 100vh;
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
        }
        .input-field {
            width: 100%;
            padding: 0.65rem 0.875rem 0.65rem 2.5rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: #1e293b;
            outline: none;
            transition: all 0.2s;
            background: #f8fafc;
        }
        .input-field:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.35);
        }
        .btn-login:active { transform: translateY(0); }
        .logo-ring {
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
        }
        .dot-pattern {
            background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        /* Titre avec effet gradient lumineux */
        .app-title {
            background: linear-gradient(135deg, #ffffff 0%, #93c5fd 50%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            text-shadow: none;
            filter: drop-shadow(0 0 20px rgba(147, 197, 253, 0.4));
        }
        .app-subtitle {
            color: #94a3b8;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            letter-spacing: 0.01em;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 dot-pattern">

<div class="w-full max-w-md">

    {{-- Logo & Titre --}}
    <div class="text-center mb-8">
        {{-- Logo --}}
        <div class="inline-flex items-center justify-center logo-ring rounded-2xl mb-5 shadow-2xl p-4">
            <i class="fa-solid fa-boxes-stacked text-white text-3xl"></i>
        </div>

        {{-- Nom de l'application — bien visible --}}
        <h1 class="app-title">GestMatériel</h1>

        {{-- Ligne décorative sous le titre --}}
        <div class="flex items-center justify-center gap-2 mt-2 mb-1">
            <div class="h-px w-12 bg-gradient-to-r from-transparent to-blue-400 opacity-60"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-blue-400 opacity-80"></div>
            <div class="h-px w-12 bg-gradient-to-l from-transparent to-blue-400 opacity-60"></div>
        </div>

        <p class="app-subtitle">Gestion du parc matériel institutionnel</p>
    </div>

    {{-- Card --}}
    <div class="card-glass rounded-2xl shadow-2xl overflow-hidden">

        {{-- Bande colorée en haut --}}
        <div class="h-1.5 bg-gradient-to-r from-blue-600 via-blue-500 to-teal-500"></div>

        <div class="p-8">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-800">Connexion</h2>
                <p class="text-slate-500 text-sm mt-1">Accès réservé au personnel autorisé</p>
            </div>

            {{-- Message d'erreur session --}}
            @if(session('error'))
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm">
                    <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Adresse email
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </span>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="votre@email.com"
                            class="input-field @error('email') border-red-400 bg-red-50 @enderror"
                        >
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Mot de passe avec œil --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Mot de passe
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input
                            :type="show ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="input-field pr-10 @error('password') border-red-400 bg-red-50 @enderror"
                        >
                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                            tabindex="-1"
                        >
                            <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Se souvenir --}}
                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                        >
                        <span class="text-sm text-slate-600 select-none">Se souvenir de moi</span>
                    </label>
                </div>

                {{-- Bouton connexion --}}
                <button type="submit" class="btn-login mt-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Se connecter
                </button>
            </form>
        </div>
    </div>

    {{-- Footer --}}
    <div class="text-center mt-6 space-y-1">
        <p class="text-slate-500 text-xs">
            <i class="fa-solid fa-shield-halved mr-1 text-slate-400"></i>
            Accès sécurisé — Sessions chiffrées
        </p>
        <p class="text-slate-500 text-xs">
            Matériel_Institut &copy; {{ date('Y') }} — Tous droits réservés
        </p>
    </div>

</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>