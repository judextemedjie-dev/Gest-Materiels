{{-- resources/views/gestionnaire/maintenances/create.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Planifier maintenance')
@section('page_title', 'Planifier une maintenance')
@section('page_subtitle', 'Programmer une intervention sur un matériel')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form method="POST" action="{{ route('gestionnaire.maintenances.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Matériel <span class="text-red-500">*</span></label>
                <select name="materiel_id" required class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('materiel_id') border-red-400 @enderror">
                    <option value="">— Sélectionner un matériel —</option>
                    @foreach($materiels as $m)
                        <option value="{{ $m->id }}" {{ old('materiel_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->designation }} ({{ $m->code_identification }}) — {{ $m->statut_label }}
                        </option>
                    @endforeach
                </select>
                @error('materiel_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Type d'intervention <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="controle"     {{ old('type') == 'controle' ? 'selected' : '' }}>Contrôle</option>
                        <option value="reparation"   {{ old('type') == 'reparation' ? 'selected' : '' }}>Réparation</option>
                        <option value="intervention" {{ old('type') == 'intervention' ? 'selected' : '' }}>Intervention</option>
                    </select>
                    @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Date planifiée <span class="text-red-500">*</span></label>
                    <input type="date" name="date_planifiee" value="{{ old('date_planifiee', date('Y-m-d')) }}" required
                           min="{{ date('Y-m-d') }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('date_planifiee') border-red-400 @enderror">
                    @error('date_planifiee')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Technicien responsable</label>
                    <input type="text" name="technicien" value="{{ old('technicien') }}"
                           placeholder="Nom du technicien ou prestataire"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Notes initiales</label>
                    <textarea name="rapport" rows="3" placeholder="Description du problème ou de l'intervention prévue..."
                              class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('rapport') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('gestionnaire.maintenances.index') }}"
                   class="flex-1 text-center border border-slate-300 text-slate-700 py-2.5 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Annuler
                </a>
                <button type="submit" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-calendar-plus mr-1"></i> Planifier
                </button>
            </div>
        </form>
    </div>
</div>
@endsection