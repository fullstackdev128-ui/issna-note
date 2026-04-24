@extends('layouts.app')

@section('title', 'Prévisualisation du Résultat Annuel')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center">
        <div class="flex items-center space-x-6">
            <div class="w-20 h-20 bg-[#1e3a8a] text-white rounded-2xl flex items-center justify-center font-black text-4xl shadow-xl shadow-blue-100">
                {{ substr($etudiant->nom, 0, 1) }}
            </div>
            <div>
                <h2 class="text-3xl font-black uppercase tracking-tighter text-gray-800">{{ $etudiant->nom_complet }}</h2>
                <p class="text-blue-600 font-bold font-mono">{{ $etudiant->matricule }} · {{ $etudiant->specialite->nom }}</p>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mt-1">{{ $resultat['libelle_niveau'] }} (S1 + S2) · Année {{ $annee->libelle }}</p>
            </div>
        </div>
        <div class="mt-6 md:mt-0 text-right">
            <div class="inline-flex flex-col items-end">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Période d'études</span>
                <span class="text-lg font-black text-gray-800">Cycle Complet</span>
            </div>
        </div>
    </div>

    <!-- Comparaison Semestrielle -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Semestre 1 -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h4 class="font-black text-gray-800 uppercase tracking-tighter">Semestre 1</h4>
                @if($resultat['resultat_s1'])
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-black uppercase tracking-widest">Validé</span>
                @else
                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-black uppercase tracking-widest">Non validé</span>
                @endif
            </div>
            <div class="p-8 space-y-6">
                @if($resultat['resultat_s1'])
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Moyenne</p>
                        <p class="text-4xl font-black text-gray-800">{{ number_format($resultat['moyenne_s1'], 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Crédits</p>
                        <p class="text-xl font-black text-gray-600">{{ $resultat['credits_valides_s1'] }} / 30</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-400 uppercase">MGP: {{ $resultat['resultat_s1']->mgp }}</span>
                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-lg font-black text-sm">{{ $resultat['resultat_s1']->grade }}</span>
                </div>
                @else
                <div class="py-12 text-center">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest italic">Résultat non encore validé</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Semestre 2 -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h4 class="font-black text-gray-800 uppercase tracking-tighter">Semestre 2</h4>
                @if($resultat['resultat_s2'])
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-black uppercase tracking-widest">Validé</span>
                @else
                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-black uppercase tracking-widest">Non validé</span>
                @endif
            </div>
            <div class="p-8 space-y-6">
                @if($resultat['resultat_s2'])
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Moyenne</p>
                        <p class="text-4xl font-black text-gray-800">{{ number_format($resultat['moyenne_s2'], 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Crédits</p>
                        <p class="text-xl font-black text-gray-600">{{ $resultat['credits_valides_s2'] }} / 30</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-400 uppercase">MGP: {{ $resultat['resultat_s2']->mgp }}</span>
                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-lg font-black text-sm">{{ $resultat['resultat_s2']->grade }}</span>
                </div>
                @else
                <div class="py-12 text-center">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest italic">Résultat non encore validé</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Synthèse Annuelle -->
    <div class="bg-gray-900 text-white rounded-3xl p-10 shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="text-xs font-black text-blue-400 uppercase tracking-widest mb-8 text-center">RÉSULTAT ANNUEL — {{ $resultat['libelle_niveau'] }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Moyenne Annuelle</p>
                    <p class="text-6xl font-black tracking-tighter">
                        {{ $resultat['moyenne_annuelle'] !== null ? number_format($resultat['moyenne_annuelle'], 2) : '---' }}<span class="text-2xl text-gray-500">/20</span>
                    </p>
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 rounded-xl border border-white/10">
                        <span class="text-sm font-bold">Crédits validés : {{ $resultat['credits_valides_total'] }} / 60</span>
                    </div>
                </div>

                <div class="space-y-4 border-l border-white/10 pl-12">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Performance Annuelle</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-5xl font-black">{{ $resultat['mgp_annuel'] ?: '0.00' }}</span>
                        <span class="text-xl font-bold text-gray-400 uppercase">MGP</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="px-4 py-1 bg-blue-600 rounded-lg font-black text-xl">{{ $resultat['grade_annuel'] ?: '-' }}</div>
                        <div class="text-xl font-bold uppercase tracking-widest">{{ $resultat['mention_annuelle'] ?: '---' }}</div>
                    </div>
                </div>

                <div class="space-y-6 border-l border-white/10 pl-12">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Validation Finale</p>
                    <form action="{{ route('resultats.annuels.valider') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="etudiant_id" value="{{ $etudiant->id }}">
                        <input type="hidden" name="annee_acad_id" value="{{ $annee->id }}">
                        
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Décision du Jury</label>
                            <select name="decision_jury" required class="w-full bg-white/5 border-2 border-white/10 rounded-2xl px-4 py-3 outline-none focus:border-blue-500 font-bold transition-all">
                                <option value="Admis(e)" {{ ($resultatEnBase?->decision_jury == 'Admis(e)') ? 'selected' : '' }} class="text-gray-800">Admis(e)</option>
                                <option value="Ajourné(e)" {{ ($resultatEnBase?->decision_jury == 'Ajourné(e)') ? 'selected' : '' }} class="text-gray-800">Ajourné(e)</option>
                                <option value="Autorisé(e) à continuer" {{ ($resultatEnBase?->decision_jury == 'Autorisé(e) à continuer') ? 'selected' : '' }} class="text-gray-800">Autorisé(e) à continuer</option>
                                <option value="Exclu(e)" {{ ($resultatEnBase?->decision_jury == 'Exclu(e)') ? 'selected' : '' }} class="text-gray-800">Exclu(e)</option>
                            </select>
                        </div>

                        @php
                            $peutValider = ($resultat['resultat_s1'] !== null || $resultat['resultat_s2'] !== null);
                        @endphp

                        <button type="submit" {{ !$peutValider ? 'disabled' : '' }} 
                                class="w-full py-4 bg-green-600 hover:bg-green-700 disabled:bg-gray-700 disabled:cursor-not-allowed text-white font-black rounded-2xl shadow-xl transition-all transform active:scale-95 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                            Valider le Résultat Annuel
                        </button>
                        @if(!$peutValider)
                            <p class="text-[10px] text-red-400 font-bold text-center uppercase tracking-widest">Au moins un résultat semestriel doit être validé.</p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <!-- Background Decor -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl"></div>
    </div>
</div>
@endsection
