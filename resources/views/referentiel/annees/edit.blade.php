@extends('layouts.app')

@section('title', 'Modifier l\'Année Académique')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">Modifier : {{ $annee->libelle }}</h1>
        <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Référentiel Académique</p>
    </div>

    <form action="{{ route('referentiel.annees.update', $annee) }}" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Libellé</label>
                <input type="text" name="libelle" value="{{ old('libelle', $annee->libelle) }}" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                @error('libelle') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Date de Début</label>
                    <input type="date" name="date_debut" value="{{ old('date_debut', $annee->date_debut->format('Y-m-d')) }}" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('date_debut') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Date de Fin</label>
                    <input type="date" name="date_fin" value="{{ old('date_fin', $annee->date_fin->format('Y-m-d')) }}" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('date_fin') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex pt-4 space-x-4">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-blue-100">
                Mettre à jour l'année
            </button>
            <a href="{{ route('referentiel.annees.index') }}" class="px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs text-gray-400 hover:bg-gray-50 transition-all text-center">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
