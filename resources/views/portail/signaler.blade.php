{{-- resources/views/portail/signaler.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signaler un problème — GestMatériel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body class="bg-slate-50 min-h-screen pb-10">

    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#b91c1c,#dc2626)" class="px-5 pt-10 pb-12">
        <a href="{{ route('portail.index', $token) }}"
           class="flex items-center gap-2 text-white/80 text-sm mb-4 hover:text-white">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-triangle-exclamation text-white"></i>
            </div>
            <div>
                <p class="text-white/70 text-xs">Signalement</p>
                <p class="text-white font-bold">Problème matériel</p>
            </div>
        </div>
    </div>

    <div class="px-4 -mt-6">

        {{-- Info matériel concerné --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-4">
            <p class="text-xs text-slate-400 mb-1">Matériel concerné</p>
            <p class="font-bold text-slate-800">{{ $affectation->materiel?->designation }}</p>
            <p class="text-xs text-slate-500 font-mono">{{ $affectation->materiel?->code_identification }}</p>
            <div class="flex items-center gap-3 mt-2 text-xs text-slate-400">
                <span><i class="fa-regular fa-calendar mr-1"></i>Assigné le {{ $affectation->date_affectation?->format('d/m/Y') }}</span>
                <span><i class="fa-solid fa-layer-group mr-1"></i>Qté : {{ $affectation->quantite }}</span>
            </div>
        </div>

        {{-- Formulaire --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h2 class="font-bold text-slate-800 mb-4">Décrire le problème</h2>

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4">
                @foreach($errors->all() as $error)
                    <p class="text-xs text-red-700"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST"
                  action="{{ route('portail.signaler.store', [$token, $affectation->id]) }}"
                  class="space-y-4">
                @csrf

                {{-- Type de problème --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Type de problème <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['panne', '🔴', 'Panne', 'border-red-200 bg-red-50 text-red-700'],
                            ['deterioration', '🟠', 'Détérioration', 'border-orange-200 bg-orange-50 text-orange-700'],
                            ['perte', '⚫', 'Perte / Vol', 'border-slate-300 bg-slate-50 text-slate-700'],
                            ['autre', '🔵', 'Autre', 'border-blue-200 bg-blue-50 text-blue-700'],
                        ] as [$val, $emoji, $label, $cls])
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="{{ $val }}"
                                   {{ old('type') == $val ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="border-2 rounded-xl p-3 text-center transition-all
                                        peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-400
                                        {{ $cls }} hover:opacity-80">
                                <p class="text-2xl mb-1">{{ $emoji }}</p>
                                <p class="text-xs font-semibold">{{ $label }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Description du problème <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="5" required
                              placeholder="Décrivez précisément le problème : que s'est-il passé ? Quand ? Comment le matériel se comporte-t-il ?"
                              class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none bg-slate-50">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Info --}}
                <div class="bg-blue-50 rounded-xl p-3">
                    <p class="text-xs text-blue-700">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Votre signalement sera immédiatement envoyé au gestionnaire qui prendra les mesures nécessaires.
                    </p>
                </div>

                {{-- Boutons --}}
                <div class="flex gap-3">
                    <a href="{{ route('portail.index', $token) }}"
                       class="flex-1 text-center border border-slate-200 text-slate-600 py-3 rounded-xl text-sm font-medium">
                        Annuler
                    </a>
                    <button type="submit"
                            class="flex-1 text-white py-3 rounded-xl text-sm font-bold transition-colors"
                            style="background:#dc2626">
                        <i class="fa-solid fa-paper-plane mr-1"></i>
                        Envoyer le signalement
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>