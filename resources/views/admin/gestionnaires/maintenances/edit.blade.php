{{-- resources/views/gestionnaire/maintenances/edit.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Mettre à jour maintenance')
@section('page_title', 'Mettre à jour la maintenance')
@section('page_subtitle', $maintenance->materiel?->designation)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        {{-- Info maintenance --}}
        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-6 flex items-start gap-4">
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-screwdriver-wrench text-orange-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800">{{ $maintenance->materiel?->designation }}</p>
                <p class="text-xs text-slate-500 mt-0.5">
                    Type : {{ ucfirst($maintenance->type) }} ·
                    Planifié le {{ $maintenance->date_planifiee?->format('d/m/Y') }} ·
                    Technicien : {{ $maintenance->technicien ?? 'Non défini' }}
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('gestionnaire.maintenances.update', $maintenance) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Statut <span class="text-red-500">*</span></label>
                    <select name="statut" required class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="planifie"  {{ old('statut', $maintenance->statut) == 'planifie' ? 'selected' : '' }}>Planifié</option>
                        <option value="en_cours"  {{ old('statut', $maintenance->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine"   {{ old('statut', $maintenance->statut) == 'termine' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Date de réalisation</label>
                    <input type="date" name="date_realisation" value="{{ old('date_realisation', $maintenance->date_realisation?->format('Y-m-d')) }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Technicien</label>
                    <input type="text" name="technicien" value="{{ old('technicien', $maintenance->technicien) }}"
                           placeholder="Nom du technicien"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Coût (FCFA)</label>
                    <input type="number" name="cout" value="{{ old('cout', $maintenance->cout) }}" min="0" step="100"
                           placeholder="0"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Rapport d'intervention</label>
                    <textarea name="rapport" rows="4" placeholder="Détails de l'intervention, résultat, pièces remplacées..."
                              class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('rapport', $maintenance->rapport) }}</textarea>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-700">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Si le statut est <strong>Terminé</strong>, le matériel repassera automatiquement en <strong>En service</strong>.
            </div>

            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('gestionnaire.maintenances.index') }}"
                   class="flex-1 text-center border border-slate-300 text-slate-700 py-2.5 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Annuler
                </a>
                <button type="submit" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection