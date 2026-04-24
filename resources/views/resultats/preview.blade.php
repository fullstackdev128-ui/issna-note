@extends('layouts.app')

@section('title', 'Prévisualisation du Résultat')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center">
        <div class="flex items-center space-x-6">
            <div class="w-20 h-20 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-black text-4xl shadow-xl shadow-blue-100">
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

    <!-- Détail par UE -->
    <div class="space-y-6">
        @foreach($resultat['detail_ues'] as $resUE)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="text-[10px] font-black px-2 py-0.5 rounded mr-2 {{ $resUE['ue']->type_ue === 'Fondamentale' ? 'bg-blue-100 text-blue-700' : ($resUE['ue']->type_ue === 'Professionnelle' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700') }}">
                            {{ strtoupper(substr($resUE['ue']->type_ue, 0, 4)) }}
                        </span>
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">UE:</span>
                        <span class="ml-2 font-black text-gray-800">{{ $resUE['ue']->code_ue }} · {{ $resUE['ue']->nom }}</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $resUE['validee'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $resUE['validee'] ? 'Validée' : 'Non Validée' }}
                        </span>
                        <span class="text-lg font-black {{ $resUE['moyenne'] >= 10 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $resUE['moyenne'] !== null ? number_format($resUE['moyenne'], 2) : 'PARTIEL' }}
                        </span>
                    </div>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Élément Constitutif (EC)</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Crédit</th>
                            <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Note Finale</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($resUE['detail_ecs'] as $detail)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-700">{{ $detail['nom'] }}</p>
                                </td>
                                <td class="px-4 py-4 text-center font-black text-gray-400">{{ $detail['credit'] }}</td>
                                <td class="px-6 py-4 text-right font-black {{ ($detail['note'] ?? 0) >= 10 ? 'text-gray-800' : 'text-red-600' }}">
                                    {{ $detail['note'] !== null ? number_format($detail['note'], 2) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <!-- Récapitulatif Final -->
    <div class="bg-gray-900 text-white rounded-3xl p-10 shadow-2xl relative overflow-hidden">
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="space-y-4">
                <p class="text-xs font-black text-blue-400 uppercase tracking-widest">Moyenne Semestrielle</p>
                <p class="text-6xl font-black tracking-tighter">
                    {{ $resultat['moyenne_sem'] !== null ? number_format($resultat['moyenne_sem'], 2) : '---' }}<span class="text-2xl text-gray-500">/20</span>
                </p>
                <div class="inline-flex items-center px-4 py-2 bg-white/10 rounded-xl border border-white/10">
                    <span class="text-sm font-bold">Crédits validés : {{ $resultat['credits_valides'] }} / {{ $resultat['total_credits'] }}</span>
                </div>
            </div>

            <div class="space-y-4 border-l border-white/10 pl-12">
                <p class="text-xs font-black text-blue-400 uppercase tracking-widest">Performance (INSES)</p>
                <div class="flex items-baseline space-x-2">
                    <span class="text-5xl font-black">{{ $resultat['mgp'] }}</span>
                    <span class="text-xl font-bold text-gray-400 uppercase">MGP</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="px-4 py-1 bg-blue-600 rounded-lg font-black text-xl">{{ $resultat['grade'] }}</div>
                    <div class="text-xl font-bold uppercase tracking-widest">{{ $resultat['mention'] }}</div>
                </div>
            </div>

            <div class="space-y-6 border-l border-white/10 pl-12">
                <p class="text-xs font-black text-blue-400 uppercase tracking-widest">Validation Finale</p>
                <form action="{{ route('resultats.valider') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="etudiant_id" value="{{ $etudiant->id }}">
                    <input type="hidden" name="annee_acad_id" value="{{ $annee->id }}">
                    <input type="hidden" name="semestre" value="{{ $semestre }}">
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Décision du Jury</label>
                        <select name="decision_jury" required class="w-full bg-white/5 border-2 border-white/10 rounded-2xl px-4 py-3 outline-none focus:border-blue-500 font-bold transition-all">
                            <option value="Admis" class="text-gray-800">Admis(e)</option>
                            <option value="Ajourne" class="text-gray-800">Ajourné(e)</option>
                            <option value="Autorise a continuer" class="text-gray-800">Autorisé à continuer</option>
                            <option value="Exclu" class="text-gray-800">Exclu(e)</option>
                        </select>
                    </div>

                    <button type="submit" {{ $resultat['est_partiel'] ? 'disabled' : '' }} 
                            class="w-full py-4 bg-green-600 hover:bg-green-700 disabled:bg-gray-700 disabled:cursor-not-allowed text-white font-black rounded-2xl shadow-xl transition-all transform active:scale-95 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        Valider et Enregistrer
                    </button>
                    @if($resultat['est_partiel'])
                        <p class="text-[10px] text-red-400 font-bold text-center">Toutes les notes doivent être saisies pour valider.</p>
                    @endif
                </form>
            </div>
        </div>
        <!-- Background Decor -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl"></div>
    </div>
</div>
@endsection
