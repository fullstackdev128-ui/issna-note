@extends('layouts.app')

@section('title', 'Calcul des Résultats')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#1e3a8a] px-8 py-6 text-white">
            <h3 class="text-2xl font-black uppercase tracking-tighter">Calculer un Résultat Semestriel</h3>
            <p class="text-blue-200 text-sm mt-1">Sélectionnez les paramètres pour générer la prévisualisation.</p>
        </div>

        <form action="{{ route('resultats.preview') }}" method="POST" class="p-8 space-y-6" x-data="rechercheEtudiant()">
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

                <!-- Input caché pour le form --> 
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
                <div x-show="etudiantSelectionne" class="mt-2 p-3 bg-green-50 border border-green-100 rounded-xl text-[10px] font-black text-green-700 uppercase tracking-widest flex items-center"> 
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                    Sélectionné : <span class="ml-1" x-text="nomSelectionne"></span> 
                </div> 
            </div>

            <!-- Info année courante (après sélection) -->
            <div x-show="etudiantSelectionne" class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Année courante</p>
                        <p class="font-bold text-blue-800" x-text="libelleAnnee"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Semestres disponibles</p>
                        <p class="text-sm font-bold text-blue-600">S1 à <span x-text="'S' + maxSemestre"></span></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Année Académique -->
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Année Académique</label>
                    <select name="annee_acad_id" required class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none font-bold text-gray-800">
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}" {{ $annee->active ? 'selected' : '' }}>{{ $annee->libelle }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Semestre -->
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Semestre</label>
                    <select name="semestre" required class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none font-bold text-gray-800">
                        <template x-for="s in Array.from({length: maxSemestre}, (_, i) => i + 1)" :key="s">
                            <option :value="s" x-text="'Semestre ' + s + ((s === semestre1Courant || s === semestre2Courant) ? ' ★ (année courante)' : '')"></option>
                        </template>
                    </select>
                </div>
            </div>

            <button type="submit" :disabled="!etudiantSelectionne" class="w-full py-5 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all transform active:scale-[0.98] flex items-center justify-center text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 7v10m-1 0h2m-1 0a1 1 0 001 1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2a1 1 0 011-1h2a1 1 0 001-1V7a1 1 0 00-1-1H5a1 1 0 01-1-1V3a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 011 1v2a1 1 0 01-1 1H9z" />
                </svg>
                Calculer et Prévisualiser
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
        semestre1Courant: 0,
        semestre2Courant: 0,
        maxSemestre: 2,
        niveauActuel: 2,
        libelleAnnee: '',
        
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
            this.recherche = this.nomSelectionne;
            this.ouvert = false;
            
            this.niveauActuel = e.niveau_actuel;
            this.semestre1Courant = (e.niveau_actuel * 2) - 1;
            this.semestre2Courant = e.niveau_actuel * 2;
            this.maxSemestre = e.duree_ans * 2;
            this.libelleAnnee = e.libelle_annee;
        }
    }
}
</script>
@endsection
