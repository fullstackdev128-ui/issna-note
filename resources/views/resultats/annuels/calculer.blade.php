@extends('layouts.app')

@section('title', 'Calcul des Résultats Annuels')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#1e3a8a] px-8 py-6 text-white">
            <h3 class="text-2xl font-black uppercase tracking-tighter">Calculer un Résultat Annuel</h3>
            <p class="text-blue-200 text-sm mt-1">Générez la synthèse annuelle pour un étudiant.</p>
        </div>

        <form action="{{ route('resultats.annuels.preview') }}" method="POST" class="p-8 space-y-6" x-data="rechercheEtudiant()">
            @csrf
            
            <!-- Recherche Étudiant Searchable -->
            <div class="space-y-2 relative">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Rechercher l'Étudiant</label>
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="recherche" 
                        @input.debounce.300ms="filtrer()" 
                        @focus="ouvert = true" 
                        placeholder="Matricule ou Nom..." 
                        class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none font-bold text-gray-800 transition-all"
                        autocomplete="off"
                    >
                </div>

                <!-- Inputs cachés pour le form --> 
                <input type="hidden" name="etudiant_id" x-model="etudiantSelectionne" required> 

                <!-- Dropdown résultats --> 
                <div x-show="ouvert && resultats.length > 0" 
                     @click.away="ouvert = false"
                     class="absolute z-50 w-full bg-white border-2 border-gray-100 rounded-2xl shadow-xl mt-1 max-h-60 overflow-y-auto overflow-x-hidden"> 
                    <template x-for="e in resultats" :key="e.id"> 
                        <div @click="selectionner(e)" 
                             class="px-6 py-4 hover:bg-blue-50 cursor-pointer border-b border-gray-50 last:border-0 transition-colors"> 
                            <div class="font-black text-gray-800 text-sm uppercase tracking-tighter" x-text="e.nom_complet"></div> 
                            <div class="text-[10px] font-bold text-blue-600 uppercase tracking-widest" x-text="e.matricule + ' · ' + e.specialite"></div> 
                        </div> 
                    </template> 
                </div> 

                <!-- Étudiant sélectionné (confirmation visuelle) --> 
                <div x-show="etudiantSelectionne" class="mt-4 p-6 bg-blue-50 border-2 border-blue-100 rounded-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Étudiant sélectionné</p>
                            <h4 class="text-lg font-black text-blue-900 uppercase tracking-tighter" x-text="nomSelectionne"></h4>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Niveau Détecté</p>
                            <span class="px-4 py-2 bg-blue-600 text-white rounded-xl font-black text-sm" x-text="libelleNiveau"></span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-100 flex items-center text-blue-600 font-bold text-xs uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Calcul basé sur : Semestre 1 + Semestre 2
                    </div>
                </div> 
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Année Académique</label>
                <select name="annee_acad_id" required class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none font-bold text-gray-800 transition-all">
                    @foreach($annees as $annee)
                        <option value="{{ $annee->id }}" {{ $annee->active ? 'selected' : '' }}>{{ $annee->libelle }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" :disabled="!etudiantSelectionne" class="w-full py-5 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all transform active:scale-[0.98] flex items-center justify-center text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 7v10m-1 0h2m-1 0a1 1 0 001 1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2a1 1 0 011-1h2a1 1 0 001-1V7a1 1 0 00-1-1H5a1 1 0 01-1-1V3a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 011 1v2a1 1 0 01-1 1H9z" />
                </svg>
                Calculer le Résultat Annuel
            </button>
        </form>
    </div>
</div>

<script>
function rechercheEtudiant() {
    return {
        recherche: '',
        resultats: [],
        ouvert: false,
        etudiantSelectionne: null,
        nomSelectionne: '',
        libelleNiveau: '',
        
        filtrer() {
            if (this.recherche.length < 2) {
                this.resultats = [];
                return;
            }
            fetch(`/api/etudiants/recherche?q=${encodeURIComponent(this.recherche)}`)
                .then(r => r.json())
                .then(data => {
                    this.resultats = data;
                    this.ouvert = true;
                });
        },
        
        selectionner(e) {
            this.etudiantSelectionne = e.id;
            this.nomSelectionne = e.nom_complet + ' (' + e.matricule + ')';
            this.libelleNiveau = e.libelle_niveau;
            this.recherche = this.nomSelectionne;
            this.ouvert = false;
        }
    }
}
</script>
@endsection
