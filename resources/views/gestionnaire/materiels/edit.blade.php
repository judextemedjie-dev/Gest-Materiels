{{-- resources/views/gestionnaire/materiels/edit.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Modifier matériel')
@section('page_title', 'Modifier le matériel')
@section('page_subtitle', $materiel->designation)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form method="POST" action="{{ route('gestionnaire.materiels.update', $materiel) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Désignation <span class="text-red-500">*</span></label>
                    <input type="text" name="designation" value="{{ old('designation', $materiel->designation) }}" required
                           class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('designation') border-red-400 @enderror">
                    @error('designation')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Code d'identification <span class="text-red-500">*</span></label>
                    <input type="text" name="code_identification" value="{{ old('code_identification', $materiel->code_identification) }}" required
                           class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-teal-500 @error('code_identification') border-red-400 @enderror">
                    @error('code_identification')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Catégorie <span class="text-red-500">*</span></label>
                    <select name="categorie_id" required class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id', $materiel->categorie_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Statut <span class="text-red-500">*</span></label>
                    <select name="statut" required class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="en_service"    {{ old('statut', $materiel->statut) == 'en_service' ? 'selected' : '' }}>En service</option>
                        <option value="en_panne"      {{ old('statut', $materiel->statut) == 'en_panne' ? 'selected' : '' }}>En panne</option>
                        <option value="en_reparation" {{ old('statut', $materiel->statut) == 'en_reparation' ? 'selected' : '' }}>En réparation</option>
                        <option value="archive"       {{ old('statut', $materiel->statut) == 'archive' ? 'selected' : '' }}>Archivé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Localisation</label>
                    <input type="text" name="localisation" value="{{ old('localisation', $materiel->localisation) }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('description', $materiel->description) }}</textarea>
                </div>
            </div>

            {{-- Info stock (non modifiable ici) --}}
            <div class="bg-slate-50 rounded-lg p-3 text-xs text-slate-500 border border-slate-200">
                <i class="fa-solid fa-circle-info text-blue-400 mr-1"></i>
                Stock actuel : <strong class="text-slate-700">{{ $materiel->quantite_stock }} unité(s)</strong>
                — Le stock est géré automatiquement via les affectations et restitutions.
            </div>

            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('gestionnaire.materiels.index') }}"
                   class="flex-1 text-center border border-slate-300 text-slate-700 py-2.5 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Annuler
                </a>
                <button type="submit"
                        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
