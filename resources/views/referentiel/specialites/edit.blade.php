@extends('layouts.app')

@section('title', 'Modifier Spécialité')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#1e3a8a] px-8 py-6 text-white">
            <h3 class="text-2xl font-black uppercase tracking-tighter">Modifier la Spécialité</h3>
            <p class="text-blue-200 text-sm mt-1">{{ $specialite->nom }}</p>
        </div>

        <form action="{{ route('referentiel.specialites.update', $specialite) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Filière parente</label>
                <select name="filiere_id" required class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none font-bold text-gray-800 transition-all">
                    @foreach($filieres as $f)
                        <option value="{{ $f->id }}" {{ old('filiere_id', $specialite->filiere_id) == $f->id ? 'selected' : '' }}>{{ $f->nom }}</option>
                    @endforeach
                </select>
                @error('filiere_id') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Code</label>
                    <input type="text" name="code" value="{{ old('code', $specialite->code) }}" required 
                        class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none font-bold text-gray-800 transition-all uppercase">
                    @error('code') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Durée du cycle (en années)</label>
                    <input type="number" name="duree_ans" value="{{ old('duree_ans', $specialite->duree_ans) }}" min="1" max="10" required 
                        class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none font-bold text-gray-800 transition-all"
                        placeholder="Ex: 3">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Type de diplôme</label>
                <select name="type_diplome" required 
                    class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none font-bold text-gray-800 transition-all">
                    <option value="BTS" {{ old('type_diplome', $specialite->type_diplome) == 'BTS' ? 'selected' : '' }}>BTS</option>
                    <option value="Licence" {{ old('type_diplome', $specialite->type_diplome) == 'Licence' ? 'selected' : '' }}>Licence</option>
                    <option value="Master1" {{ old('type_diplome', $specialite->type_diplome) == 'Master1' ? 'selected' : '' }}>Master 1</option>
                    <option value="Master2" {{ old('type_diplome', $specialite->type_diplome) == 'Master2' ? 'selected' : '' }}>Master 2</option>
                </select>
                @error('type_diplome') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Nom de la spécialité</label>
                <input type="text" name="nom" value="{{ old('nom', $specialite->nom) }}" required 
                    class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none font-bold text-gray-800 transition-all">
                @error('nom') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-2xl">
                <input type="hidden" name="actif" value="0">
                <input type="checkbox" name="actif" value="1" id="actif" {{ old('actif', $specialite->actif) ? 'checked' : '' }}
                    class="w-6 h-6 rounded-lg border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="actif" class="font-black text-gray-700 uppercase tracking-tighter cursor-pointer">Spécialité active</label>
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all transform active:scale-95 uppercase tracking-widest text-xs">
                    Mettre à jour
                </button>
                <a href="{{ route('referentiel.specialites.index') }}" class="flex-1 py-4 bg-gray-100 hover:bg-gray-200 text-gray-600 font-black rounded-2xl transition-all text-center uppercase tracking-widest text-xs">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
