{{-- resources/views/components/footer.blade.php --}}
<footer class="bg-white border-t border-slate-200 px-4 lg:px-6 py-3 flex-shrink-0">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-2">

        {{-- Gauche : nom app + version --}}
        <div class="flex items-center gap-2">
            <div class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#1e3a5f,#2563eb)">
                <i class="fa-solid fa-boxes-stacked text-white" style="font-size:8px"></i>
            </div>
            <span class="text-xs font-semibold text-slate-600">GestMatériel</span>
            <span class="text-xs text-slate-300">|</span>
            <span class="text-xs text-slate-400">v1.0.0</span>
        </div>

        {{-- Centre : copyright --}}
        <div class="text-xs text-slate-400 text-center">
            &copy; {{ date('Y') }} <span class="font-medium text-slate-500">Materiel_Institut</span>
            — Tous droits réservés
        </div>

        {{-- Droite : statut système --}}
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
                <span class="text-xs text-slate-400">Système opérationnel</span>
            </div>
            <span class="text-xs text-slate-300">|</span>
            <span class="text-xs text-slate-400">
                <i class="fa-solid fa-clock mr-1 text-slate-300"></i>{{ now()->format('H:i') }}
            </span>
        </div>

    </div>
</footer>