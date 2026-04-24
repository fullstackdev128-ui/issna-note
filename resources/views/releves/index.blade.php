@extends('layouts.app')

@section('title', 'Génération des Relevés de Notes')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-[#1e3a8a] p-8 text-white">
            <h1 class="text-3xl font-black uppercase tracking-tighter">Générer un Relevé de Notes</h1>
            <p class="text-blue-200 font-bold">Sélectionnez l'étudiant et les semestres à inclure.</p>
        </div>

        <form action="{{ route('releves.generer') }}" method="POST" class="p-8 space-y-8" x-data="rechercheEtudiant()">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Étudiant Searchable -->
                <div class="space-y-2 relative">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Étudiant</label>
                    <input 
                        type="text" 
                        x-model="recherche" 
                        @input.debounce.300ms="filtrer()" 
                        @focus="ouvert = true" 
                        placeholder="Rechercher par nom ou matricule..." 
                        class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 font-bold text-gray-700 focus:border-blue-500 focus:bg-white outline-none transition-all"
                        autocomplete="off"
                    >
                    
                    <!-- Input caché pour le form --> 
                    <input type="hidden" name="etudiant_id" x-model="etudiantSelectionne"> 
                    
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

                <!-- Année Académique -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Année Académique</label>
                    <select name="annee_acad_id" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 font-bold text-gray-700 focus:border-blue-500 focus:bg-white outline-none transition-all">
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Semestres -->
            <div class="space-y-4" x-show="etudiantSelectionne">
                <div class="flex justify-between items-center">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Semestres à inclure</label>
                    <button type="button" @click="selectionnerAnneeComplete()" 
                            class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span x-text="'↗ Année complète : ' + libelleNiveau"></span>
                    </button>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <template x-for="s in Array.from({length: maxSemestre}, (_, i) => i + 1)" :key="s">
                        <label class="relative flex items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:bg-blue-50 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="checkbox" name="semestres[]" :value="s" x-model="semestresCoches" class="hidden peer">
                            <div class="flex flex-col">
                                <span class="font-black text-gray-800 uppercase tracking-tighter" x-text="'Semestre ' + s"></span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase" x-text="'S' + s"></span>
                            </div>
                            <div class="absolute right-4 hidden peer-checked:block text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </label>
                    </template>
                </div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">
                    Note : Sélectionnez deux semestres consécutifs d'une même année (ex: S1+S2, S3+S4) pour générer un relevé annuel.
                </p>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#1e3a8a] text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-blue-200 hover:scale-105 active:scale-95 transition-all flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Générer le PDF
                </button>
            </div>
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
        semestresCoches: [],
        maxSemestre: 0,
        niveauActuel: 0,

        init() {
            const urlParams = new URLSearchParams(window.location.search);
            const etudiantId = urlParams.get('etudiante_id') || urlParams.get('etudiant_id');
            const semestre = urlParams.get('semestre');

            if (etudiantId) {
                fetch(`/api/etudiants/recherche?q=id:${etudiantId}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.length > 0) {
                            this.selectionner(data[0]);
                            if (semestre) {
                                this.semestresCoches = [semestre.toString()];
                            }
                        }
                    });
            }
        },
        
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
            this.libelleNiveau = e.libelle_annee || e.libelle_niveau;
            this.nomSelectionne = e.nom_complet + ' (' + e.matricule + ')';
            this.recherche = this.nomSelectionne;
            this.ouvert = false;
            this.semestresCoches = [];
            
            this.niveauActuel = e.niveau_actuel;
            this.maxSemestre = e.duree_ans * 2;
        },

        selectionnerAnneeComplete() {
            if (!this.etudiantSelectionne) return;
            const semA = (this.niveauActuel * 2) - 1;
            const semB = (this.niveauActuel * 2);
            this.semestresCoches = [semA.toString(), semB.toString()];
        }
    }
}
</script>
@endsection
