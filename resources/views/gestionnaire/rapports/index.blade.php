{{-- resources/views/gestionnaire/rapports/index.blade.php --}}
@extends('layouts.gestionnaire')
@section('title', 'Rapports')
@section('page_title', 'Rapports & Exports')
@section('page_subtitle', 'Générer et exporter les rapports du parc matériel')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    {{-- Inventaire --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-boxes-stacked text-blue-600"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-800">Inventaire complet</h3>
                <p class="text-xs text-slate-500 mt-0.5">Tous les matériels avec stock, statut et localisation</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('gestionnaire.rapports.inventaire') }}"
               class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 py-2 rounded-lg text-xs font-medium transition-colors">
                <i class="fa-solid fa-eye mr-1"></i> Aperçu
            </a>
            <a href="{{ route('gestionnaire.rapports.pdf', 'inventaire') }}"
               class="flex-1 text-center bg-red-100 hover:bg-red-200 text-red-700 py-2 rounded-lg text-xs font-medium transition-colors">
                <i class="fa-solid fa-file-pdf mr-1"></i> PDF
            </a>
            <a href="{{ route('gestionnaire.rapports.excel', 'inventaire') }}"
               class="flex-1 text-center bg-green-100 hover:bg-green-200 text-green-700 py-2 rounded-lg text-xs font-medium transition-colors">
                <i class="fa-solid fa-file-excel mr-1"></i> Excel
            </a>
        </div>
    </div>

    {{-- Affectations --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-right-left text-teal-600"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-800">Rapport des affectations</h3>
                <p class="text-xs text-slate-500 mt-0.5">Historique complet des affectations et restitutions</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('gestionnaire.rapports.affectations') }}"
               class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 py-2 rounded-lg text-xs font-medium transition-colors">
                <i class="fa-solid fa-eye mr-1"></i> Aperçu
            </a>
            <a href="{{ route('gestionnaire.rapports.pdf', 'affectations') }}"
               class="flex-1 text-center bg-red-100 hover:bg-red-200 text-red-700 py-2 rounded-lg text-xs font-medium transition-colors">
                <i class="fa-solid fa-file-pdf mr-1"></i> PDF
            </a>
            <a href="{{ route('gestionnaire.rapports.excel', 'affectations') }}"
               class="flex-1 text-center bg-green-100 hover:bg-green-200 text-green-700 py-2 rounded-lg text-xs font-medium transition-colors">
                <i class="fa-solid fa-file-excel mr-1"></i> Excel
            </a>
        </div>
    </div>
</div>
@endsection
