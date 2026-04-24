@extends('layouts.app')

@section('title', 'Nouvel Étudiant')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Inscription d'un étudiant</h2>
        <a href="{{ route('etudiants.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Retour à la liste
        </a>
    </div>

    <form action="{{ route('etudiants.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" 
          x-data="{ 
            filiere_id: '', 
            specialites: {{ $specialites->toJson() }},
            get filteredSpecialites() {
                if (!this.filiere_id) return [];
                return this.specialites.filter(s => s.filiere_id == this.filiere_id);
            }
          }">
        @csrf
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- État Civil -->
            <div class="col-span-full border-b border-gray-100 pb-2">
                <h3 class="text-lg font-bold text-blue-800">État Civil</h3>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('nom') border-red-500 @enderror" placeholder="Ex: BIEGWEN KERE">
                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Prénoms <span class="text-red-500">*</span></label>
                <input type="text" name="prenoms" value="{{ old('prenoms') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('prenoms') border-red-500 @enderror" placeholder="Ex: Doriane Jeanine">
                @error('prenoms') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Date de naissance <span class="text-red-500">*</span></label>
                <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('date_naissance') border-red-500 @enderror">
                @error('date_naissance') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Lieu de naissance</label>
                <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Ex: Douala">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Genre <span class="text-red-500">*</span></label>
                <select name="genre" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="M" {{ old('genre') == 'M' ? 'selected' : '' }}>Masculin</option>
                    <option value="F" {{ old('genre') == 'F' ? 'selected' : '' }}>Féminin</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Ex: 699001122">
            </div>

            <div class="col-span-full">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('email') border-red-500 @enderror" placeholder="etudiant@exemple.com">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Lieu de résidence</label>
                <input type="text" name="lieu_residence" value="{{ old('lieu_residence') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Ex: Akwa, Douala">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Établissement de provenance</label>
                <input type="text" name="etablissement_provenance" value="{{ old('etablissement_provenance') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Lycée de Joss...">
            </div>

            <!-- Inscription -->
            <div class="col-span-full border-b border-gray-100 pb-2 mt-4">
                <h3 class="text-lg font-bold text-blue-800">Inscription Académique</h3>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Campus <span class="text-red-500">*</span></label>
                <select name="campus_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach($campus as $c)
                        <option value="{{ $c->id }}" {{ old('campus_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Niveau <span class="text-red-500">*</span></label>
                <select name="niveau_actuel" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    @for($i=1; $i<=5; $i++)
                        <option value="{{ $i }}" {{ old('niveau_actuel') == $i ? 'selected' : '' }}>Niveau {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Filière <span class="text-red-500">*</span></label>
                <select x-model="filiere_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Sélectionner une filière</option>
                    @foreach($filieres as $f)
                        <option value="{{ $f->id }}">{{ $f->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Spécialité <span class="text-red-500">*</span></label>
                <select name="specialite_id" required :disabled="!filiere_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none disabled:bg-gray-50 disabled:cursor-not-allowed">
                    <option value="">Sélectionner une spécialité</option>
                    <template x-for="spec in filteredSpecialites" :key="spec.id">
                        <option :value="spec.id" x-text="spec.nom" :selected="spec.id == '{{ old('specialite_id') }}'"></option>
                    </template>
                </select>
                @error('specialite_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Parents -->
            <div class="col-span-full border-b border-gray-100 pb-2 mt-4">
                <h3 class="text-lg font-bold text-blue-800">Informations Parent/Tuteur</h3>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom du parent / tuteur</label>
                <input type="text" name="nom_parent" value="{{ old('nom_parent') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nom complet">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Téléphone du parent</label>
                <input type="text" name="tel_parent" value="{{ old('tel_parent') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Ex: 677001122">
            </div>
        </div>

        <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
            <button type="reset" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 transition-colors">
                Annuler
            </button>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-lg transition-all transform active:scale-95">
                Enregistrer l'étudiant
            </button>
        </div>
    </form>
</div>
@endsection
