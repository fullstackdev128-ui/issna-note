@extends('layouts.app')

@section('title', 'Résultat Annuel Validé')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Header Success -->
    <div class="bg-green-600 rounded-3xl p-8 shadow-xl text-white flex justify-between items-center relative overflow-hidden">
        <div class="relative z-10 flex items-center space-x-6">
            <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center font-black text-4xl shadow-inner">
                {{ substr($etudiant->nom, 0, 1) }}
            </div>
            <div>
                <h2 class="text-3xl font-black uppercase tracking-tighter">{{ $etudiant->nom_complet }}</h2>
                <p class="text-green-100 font-bold font-mono">{{ $etudiant->matricule }} · {{ $etudiant->specialite->nom }}</p>
                <div class="flex items-center mt-2 space-x-3">
                    <span class="px-3 py-1 bg-white/20 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $resultatCalcul['libelle_niveau'] }}</span>
                    <span class="px-3 py-1 bg-white/20 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $annee->libelle }}</span>
                </div>
            </div>
        </div>
        <div class="relative z-10 text-right">
            <p class="text-[10px] font-black text-green-200 uppercase tracking-widest">Statut Final</p>
            <p class="text-3xl font-black uppercase tracking-tighter">{{ $resultatAnnuel->decision_jury }}</p>
        </div>
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Synthèse Annuelle -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Moyenne Annuelle</p>
                <p class="text-5xl font-black text-gray-800">{{ number_format($resultatAnnuel->moyenne_annuelle, 2) }}<span class="text-xl text-gray-300">/20</span></p>
            </div>
            <div class="space-y-2 border-l border-gray-50 pl-8">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">MGP / Grade</p>
                <div class="flex items-center space-x-3">
                    <span class="text-3xl font-black text-blue-600">{{ $resultatAnnuel->mgp_annuel }}</span>
                    <span class="px-3 py-1 bg-blue-600 text-white rounded-lg font-black text-xl">{{ $resultatAnnuel->grade_annuel }}</span>
                </div>
            </div>
            <div class="space-y-2 border-l border-gray-50 pl-8">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mention</p>
                <p class="text-xl font-black text-gray-700 uppercase tracking-widest">{{ $resultatAnnuel->mention_annuelle }}</p>
            </div>
            <div class="space-y-2 border-l border-gray-50 pl-8">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Crédits Validés</p>
                <p class="text-3xl font-black text-gray-800">{{ $resultatAnnuel->credits_valides_total }} <span class="text-lg text-gray-400">/ 60</span></p>
            </div>
        </div>
    </div>

    <!-- Détail par Semestre -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                <h4 class="font-black text-gray-800 uppercase tracking-tighter">Semestre 1</h4>
            </div>
            <div class="p-8">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Moyenne</p>
                        <p class="text-3xl font-black text-gray-800">{{ $resultatAnnuel->moyenne_s1 !== null ? number_format($resultatAnnuel->moyenne_s1, 2) : '--.--' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Crédits</p>
                        <p class="text-xl font-black text-gray-600">{{ $resultatAnnuel->credits_valides_s1 }} / 30</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                <h4 class="font-black text-gray-800 uppercase tracking-tighter">Semestre 2</h4>
            </div>
            <div class="p-8">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Moyenne</p>
                        <p class="text-3xl font-black text-gray-800">{{ $resultatAnnuel->moyenne_s2 !== null ? number_format($resultatAnnuel->moyenne_s2, 2) : '--.--' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Crédits</p>
                        <p class="text-xl font-black text-gray-600">{{ $resultatAnnuel->credits_valides_s2 }} / 30</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center pt-8">
        <a href="{{ route('resultats.annuels.calculer') }}" class="px-10 py-4 bg-gray-800 text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl hover:bg-gray-900 transition-all flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
            </svg>
            Nouveau Calcul
        </a>
    </div>
</div>
@endsection
