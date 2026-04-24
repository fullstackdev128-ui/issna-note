@extends('layouts.app')

@section('title', "Modifier Étudiant : {$etudiant->matricule}")

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Modification de l'étudiant</h2>
        <a href="{{ route('etudiants.show', $etudiant) }}" class="text-gray-600 hover:text-gray-900 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Annuler et voir la fiche
        </a>
    </div>

    <form action="{{ route('etudiants.update', $etudiant) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" 
          x-data="{ 
            filiere_id: '{{ $etudiant->specialite->filiere_id }}', 
            specialites: {{ $specialites->toJson() }},
            get filteredSpecialites() {
                if (!this.filiere_id) return [];
                return this.specialites.filter(s => s.filiere_id == this.filiere_id);
            }
          }">
        @csrf
        @method('PUT')
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- État Civil -->
            <div class="col-span-full border-b border-gray-100 pb-2">
                <h3 class="text-lg font-bold text-blue-800">État Civil & Statut</h3>
            </div>

            <div class="col-span-full">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Matricule (non modifiable)</label>
                <input type="text" value="{{ $etudiant->matricule }}" disabled class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-500 font-mono font-bold">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ old('nom', $etudiant->nom) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('nom') border-red-500 @enderror">
                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Prénoms <span class="text-red-500">*</span></label>
                <input type="text" name="prenoms" value="{{ old('prenoms', $etudiant->prenoms) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('prenoms') border-red-500 @enderror">
                @error('prenoms') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Date de naissance <span class="text-red-500">*</span></label>
                <input type="date" name="date_naissance" value="{{ old('date_naissance', $etudiant->date_naissance->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Lieu de naissance</label>
                <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance', $etudiant->lieu_naissance) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Genre <span class="text-red-500">*</span></label>
                <select name="genre" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="M" {{ old('genre', $etudiant->genre) == 'M' ? 'selected' : '' }}>Masculin</option>
                    <option value="F" {{ old('genre', $etudiant->genre) == 'F' ? 'selected' : '' }}>Féminin</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Statut Actuel <span class="text-red-500">*</span></label>
                <select name="statut" required class="w-full px-4 py-2 border border-blue-200 bg-blue-50 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-bold text-blue-800">
                    <option value="actif" {{ old('statut', $etudiant->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="suspendu" {{ old('statut', $etudiant->statut) == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    <option value="diplome" {{ old('statut', $etudiant->statut) == 'diplome' ? 'selected' : '' }}>Diplômé</option>
                    <option value="abandonne" {{ old('statut', $etudiant->statut) == 'abandonne' ? 'selected' : '' }}>Abandonné</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $etudiant->telephone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $etudiant->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Inscription -->
            <div class="col-span-full border-b border-gray-100 pb-2 mt-4">
                <h3 class="text-lg font-bold text-blue-800">Inscription Académique</h3>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Campus <span class="text-red-500">*</span></label>
                <select name="campus_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach($campus as $c)
                        <option value="{{ $c->id }}" {{ old('campus_id', $etudiant->campus_id) == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Niveau <span class="text-red-500">*</span></label>
                <select name="niveau_actuel" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    @for($i=1; $i<=5; $i++)
                        <option value="{{ $i }}" {{ old('niveau_actuel', $etudiant->niveau_actuel) == $i ? 'selected' : '' }}>Niveau {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Filière <span class="text-red-500">*</span></label>
                <select x-model="filiere_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach($filieres as $f)
                        <option value="{{ $f->id }}">{{ $f->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Spécialité <span class="text-red-500">*</span></label>
                <select name="specialite_id" required :disabled="!filiere_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <template x-for="spec in filteredSpecialites" :key="spec.id">
                        <option :value="spec.id" x-text="spec.nom" :selected="spec.id == '{{ old('specialite_id', $etudiant->specialite_id) }}'"></option>
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
                <input type="text" name="nom_parent" value="{{ old('nom_parent', $etudiant->nom_parent) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Téléphone du parent</label>
                <input type="text" name="tel_parent" value="{{ old('tel_parent', $etudiant->tel_parent) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-lg transition-all">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection
