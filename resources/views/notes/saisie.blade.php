@extends('layouts.app')

@section('title', 'Saisie des Notes')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="{ 
    step: {{ $etudiant ? 2 : 1 }},
    search: '',
    etudiants: [],
    loading: false,
    
    async searchEtudiants() {
        if (this.search.length < 2) return;
        this.loading = true;
        const res = await fetch(`{{ route('notes.saisie') }}?search=${this.search}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        // Pour simplifier, on recharge la page avec le paramètre search si pas en AJAX
        window.location.href = `{{ route('notes.saisie') }}?search=${this.search}`;
    }
}">
    <!-- Étape 1 : Sélection Étudiant -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 flex items-center">
                <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3 text-sm">1</span>
                Sélection de l'Étudiant
            </h3>
            @if($etudiant)
                <a href="{{ route('notes.saisie') }}" class="text-blue-600 hover:underline text-sm font-semibold">Changer d'étudiant</a>
            @endif
        </div>
        
        <div class="p-6">
            @if(!$etudiant)
                <form action="{{ route('notes.saisie') }}" method="GET" class="flex space-x-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par matricule ou nom..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-lg font-semibold hover:bg-gray-900 transition-colors">
                        Rechercher
                    </button>
                </form>

                @if(request('search') && count($etudiants) > 0)
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($etudiants as $e)
                            <a href="{{ route('notes.saisie', ['etudiant_id' => $e->id, 'search' => request('search')]) }}" class="p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all flex items-center">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-4">
                                    {{ substr($e->nom, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $e->nom_complet }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $e->matricule }} · {{ $e->specialite->nom }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @elseif(request('search'))
                    <p class="mt-4 text-center text-gray-500 italic">Aucun étudiant trouvé pour "{{ request('search') }}"</p>
                @endif
            @else
                <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-100 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-600 text-white rounded-xl shadow-md flex items-center justify-center font-black text-xl mr-4">
                            {{ substr($etudiant->nom, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xl font-black text-gray-800 uppercase tracking-tighter">{{ $etudiant->nom_complet }}</p>
                            <p class="text-sm font-bold text-blue-600 font-mono">{{ $etudiant->matricule }} · {{ $etudiant->specialite->nom }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Année Académique</p>
                        <p class="text-lg font-black text-gray-800">{{ $anneeActive->libelle }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Étape 2 : Sélection Semestre & UE -->
    @if($etudiant)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3 text-sm">2</span>
                    Sélection du Semestre
                </h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-4">
                    @php
                        $annee = $etudiant->niveau_actuel;
                        $semA = ($annee * 2) - 1;
                        $semB = $annee * 2;
                    @endphp
                    @foreach([$semA, $semB] as $s)
                        <a href="{{ route('notes.saisie', ['etudiant_id' => $etudiant->id, 'semestre' => $s, 'search' => request('search')]) }}" 
                           class="px-8 py-3 rounded-xl font-black text-lg transition-all border-2 {{ request('semestre') == $s ? 'bg-blue-600 text-white border-blue-600 shadow-lg scale-105' : 'bg-white text-gray-400 border-gray-100 hover:border-blue-200 hover:text-blue-600' }}">
                            S{{ $s }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Étape 3 : Grille de Saisie -->
    @if($etudiant && request('semestre'))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3 text-sm">3</span>
                    Grille de Saisie · Semestre {{ request('semestre') }}
                </h3>
                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Échelle 0-20 · Pas 0.25</span>
            </div>
            
            <form action="{{ route('notes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="etudiant_id" value="{{ $etudiant->id }}">
                <input type="hidden" name="annee_acad_id" value="{{ $anneeActive->id }}">
                <input type="hidden" name="semestre" value="{{ request('semestre') }}">

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">UE / Matière</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Crédit</th>
                                <th class="px-6 py-4 text-xs font-black text-blue-600 uppercase tracking-widest text-center">CC (40%)</th>
                                <th class="px-6 py-4 text-xs font-black text-blue-600 uppercase tracking-widest text-center">SN (60%)</th>
                                <th class="px-6 py-4 text-xs font-black text-red-600 uppercase tracking-widest text-center">RP (Rattrap.)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($ues as $ue)
                                <tr class="bg-blue-50/30">
                                    <td colspan="5" class="px-6 py-2 text-sm font-black text-blue-800 uppercase tracking-tighter">
                                        {{ $ue->code_ue }} · {{ $ue->nom }}
                                    </td>
                                </tr>
                                @foreach($ue->elementConstitutifs as $ec)
                                    @php
                                        $existingNotes = \App\Models\Note::where([
                                            'etudiant_id' => $etudiant->id,
                                            'element_constitutif_id' => $ec->id,
                                            'annee_acad_id' => $anneeActive->id,
                                            'semestre' => request('semestre')
                                        ])->get()->keyBy('type_examen');
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors" x-data="{ hasSN: {{ $existingNotes->has('SN') ? 'true' : 'false' }} }">
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-800">{{ $ec->nom }}</p>
                                            <p class="text-xs text-gray-400 font-mono">{{ $ec->code_ec }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center font-black text-gray-400">{{ $ec->credit }}</td>
                                        <td class="px-6 py-4">
                                            <input type="number" name="notes[{{ $ec->id }}][CC]" step="0.25" min="0" max="20" 
                                                   value="{{ old("notes.$ec->id.CC", $existingNotes->has('CC') ? $existingNotes['CC']->valeur : '') }}"
                                                   class="w-20 mx-auto block px-2 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-center font-bold text-blue-700">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" name="notes[{{ $ec->id }}][SN]" step="0.25" min="0" max="20" 
                                                   value="{{ old("notes.$ec->id.SN", $existingNotes->has('SN') ? $existingNotes['SN']->valeur : '') }}"
                                                   @input="hasSN = $el.value !== ''"
                                                   class="w-20 mx-auto block px-2 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-center font-bold text-blue-700">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" name="notes[{{ $ec->id }}][RP]" step="0.25" min="0" max="20" 
                                                   value="{{ old("notes.$ec->id.RP", $existingNotes->has('RP') ? $existingNotes['RP']->valeur : '') }}"
                                                   :disabled="!hasSN"
                                                   :class="!hasSN ? 'bg-gray-100 cursor-not-allowed text-gray-400' : 'text-red-700 border-red-100 bg-red-50 focus:ring-red-500'"
                                                   class="w-20 mx-auto block px-2 py-2 border border-gray-200 rounded-lg outline-none text-center font-bold">
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                        Aucune unité d'enseignement trouvée pour ce semestre.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($ues) > 0)
                    <div class="p-8 bg-gray-50 border-t border-gray-100 flex justify-center">
                        <button type="submit" class="px-12 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-xl shadow-2xl shadow-blue-200 transition-all transform active:scale-95 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                            Enregistrer toutes les notes
                        </button>
                    </div>
                @endif
            </form>
        </div>
    @endif
</div>
@endsection
