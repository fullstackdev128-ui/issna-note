@extends('layouts.app')

@section('title', 'Résultat Semestriel Validé')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Badge de succès -->
    <div class="bg-green-100 border-2 border-green-200 p-6 rounded-3xl flex items-center justify-between shadow-sm">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-500 text-white rounded-2xl flex items-center justify-center shadow-lg mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-black text-green-800 uppercase tracking-tighter">Résultat Officiel Validé</h3>
                <p class="text-green-700 text-sm font-bold">Ce résultat a été enregistré le {{ $resultatEnBase->date_calcul->format('d/m/Y à H:i') }}.</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('releves') }}?etudiant_id={{ $etudiant->id }}&semestre={{ $semestre }}&annee_acad_id={{ $annee->id }}" 
               class="px-6 py-2 bg-white text-blue-700 border-2 border-blue-200 rounded-xl font-black text-sm hover:bg-blue-50 transition-all flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Relevé PDF
            </a>
            <a href="{{ route('resultats.calculer') }}" class="px-6 py-2 bg-gray-800 text-white rounded-xl font-black text-sm hover:bg-gray-900 transition-all">Nouveau calcul</a>
        </div>
    </div>

    <!-- En-tête Étudiant -->
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center">
        <div class="flex items-center space-x-6">
            <div class="w-20 h-20 bg-[#1e3a8a] text-white rounded-2xl flex items-center justify-center font-black text-4xl">
                {{ substr($etudiant->nom, 0, 1) }}
            </div>
            <div>
                <h2 class="text-3xl font-black uppercase tracking-tighter text-gray-800">{{ $etudiant->nom_complet }}</h2>
                <p class="text-blue-600 font-bold font-mono">{{ $etudiant->matricule }} · {{ $etudiant->specialite->nom }}</p>
            </div>
        </div>
        <div class="mt-6 md:mt-0 text-right">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Semestre {{ $semestre }}</p>
            <p class="text-xl font-black text-gray-800">{{ $annee->libelle }}</p>
        </div>
    </div>

    <!-- Détail des UE -->
    <div class="space-y-6">
        @foreach($resultatCalcul['detail_ues'] as $resUE)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="text-[10px] font-black px-2 py-0.5 rounded mr-2 {{ $resUE['ue']->type_ue === 'Fondamentale' ? 'bg-blue-100 text-blue-700' : ($resUE['ue']->type_ue === 'Professionnelle' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700') }}">
                            {{ strtoupper(substr($resUE['ue']->type_ue, 0, 4)) }}
                        </span>
                        <span class="font-black text-gray-800">{{ $resUE['ue']->code_ue }} · {{ $resUE['ue']->nom }}</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $resUE['validee'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $resUE['validee'] ? 'Validée' : 'Non Validée' }}
                        </span>
                        <span class="text-lg font-black {{ ($resUE['moyenne'] ?? 0) >= 10 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($resUE['moyenne'], 2) }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Récapitulatif Final -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm text-center">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Moyenne</p>
            <p class="text-4xl font-black text-gray-800">{{ number_format($resultatEnBase->moyenne_sem, 2) }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm text-center">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">MGP</p>
            <p class="text-4xl font-black text-blue-600">{{ $resultatEnBase->mgp }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm text-center">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Grade / Mention</p>
            <p class="text-2xl font-black text-gray-800">{{ $resultatEnBase->grade }}</p>
            <p class="text-xs font-bold text-gray-500 uppercase">{{ $resultatEnBase->mention }}</p>
        </div>
        <div class="bg-gray-900 p-8 rounded-3xl border border-gray-800 shadow-xl text-center flex flex-col justify-center">
            <p class="text-xs font-black text-blue-400 uppercase tracking-widest mb-2">Décision</p>
            <p class="text-xl font-black text-white uppercase tracking-tighter">{{ $resultatEnBase->decision_jury }}</p>
        </div>
    </div>
</div>
@endsection
