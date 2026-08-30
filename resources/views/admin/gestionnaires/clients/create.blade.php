{{-- resources/views/gestionnaire/clients/create.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Nouveau client')
@section('page_title', 'Créer un client')
@section('page_subtitle', 'Enregistrer un nouveau client')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form method="POST" action="{{ route('gestionnaire.clients.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom complet / Service <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                       placeholder="Ex: Direction Générale"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('nom') border-red-400 @enderror">
                @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Adresse email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="service@institution.cm"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Contact (téléphone) <span class="text-red-500">*</span></label>
                <input type="text" name="contact" value="{{ old('contact') }}" required
                       placeholder="Ex: 699001234"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('contact') border-red-400 @enderror">
                @error('contact')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('gestionnaire.clients.index') }}"
                   class="flex-1 text-center border border-slate-300 text-slate-700 py-2.5 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Annuler
                </a>
                <button type="submit"
                        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-user-plus mr-1"></i> Créer le client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection