@extends('layouts.app')

@section('title', "Récapitulatif des Notes : {$etudiant->nom_complet}")

@section('content')
<div class="max-w-6xl mx-auto space-y-8" x-data="{ 
    showModal: false, 
    currentNote: { id: '', val: '', type: '', ec: '' },
    openEdit(id, val, type, ec) {
        this.currentNote = { id, val, type, ec };
        this.showModal = true;
    }
}">
    <!-- Header -->
    <div class="bg-[#1e3a8a] text-white rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center space-x-6">
                <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center font-black text-4xl border border-white/20 shadow-inner">
                    {{ substr($etudiant->nom, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-4xl font-black uppercase tracking-tighter leading-none">{{ $etudiant->nom_complet }}</h2>
                    <p class="text-blue-200 mt-2 font-bold tracking-widest uppercase text-xs">{{ $etudiant->specialite->nom }} · {{ $etudiant->specialite->filiere->nom }}</p>
                </div>
            </div>
            <div class="mt-6 md:mt-0 flex items-center space-x-4 bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                <div class="text-center px-4 border-r border-white/10">
                    <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest">Matricule</p>
                    <p class="text-xl font-black font-mono">{{ $etudiant->matricule }}</p>
                </div>
                <div class="text-center px-4 border-r border-white/10">
                    <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest">Niveau</p>
                    <p class="text-xl font-black">{{ $etudiant->niveau_actuel }}</p>
                </div>
                <div class="text-center px-4">
                    <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest">Année</p>
                    <p class="text-xl font-black">{{ $etudiant->anneeAcademique->libelle }}</p>
                </div>
            </div>
        </div>
        <!-- Background Decor -->
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-blue-400/10 rounded-full blur-2xl"></div>
    </div>

    <!-- Notes par Semestre -->
    @forelse($notes as $semestre => $ues)
        <div class="space-y-4">
            <h3 class="text-2xl font-black text-gray-800 flex items-center px-4">
                <span class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center mr-3 text-lg shadow-lg shadow-blue-200">S{{ $semestre }}</span>
                Semestre {{ $semestre }}
            </h3>

            <div class="grid grid-cols-1 gap-6">
                @foreach($ues as $ue_id => $ue_notes)
                    @php 
                        $ue = \App\Models\UniteEnseignement::find($ue_id); 
                    @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50/80 px-6 py-3 border-b border-gray-100 flex justify-between items-center">
                            <span class="text-sm font-black text-gray-400 uppercase tracking-widest">{{ $ue->code_ue }} · {{ $ue->nom }}</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-[10px] font-black rounded-full uppercase">{{ $ue->type_ue }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50/30">
                                    <tr>
                                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Matière (EC)</th>
                                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Crédit</th>
                                        <th class="px-4 py-3 text-[10px] font-black text-blue-600 uppercase tracking-widest text-center">CC (40%)</th>
                                        <th class="px-4 py-3 text-[10px] font-black text-blue-600 uppercase tracking-widest text-center">SN (60%)</th>
                                        <th class="px-4 py-3 text-[10px] font-black text-red-600 uppercase tracking-widest text-center">RP</th>
                                        <th class="px-6 py-3 text-[10px] font-black text-gray-800 uppercase tracking-widest text-center">Note Finale</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @php
                                        // On regroupe les notes par EC
                                        $ec_notes = $ue_notes->groupBy('element_constitutif_id');
                                    @endphp
                                    @foreach($ec_notes as $ec_id => $notes_ec)
                                        @php
                                            $ec = $notes_ec->first()->elementConstitutif;
                                            $cc = $notes_ec->firstWhere('type_examen', 'CC');
                                            $sn = $notes_ec->firstWhere('type_examen', 'SN');
                                            $rp = $notes_ec->firstWhere('type_examen', 'RP');

                                            // Calcul de la note finale
                                            $noteFinale = null;
                                            $status = 'partial'; // partial, fail, pass
                                            
                                            if ($rp) {
                                                $noteFinale = $rp->valeur;
                                            } elseif ($cc && $sn) {
                                                $noteFinale = ($cc->valeur * 0.4) + ($sn->valeur * 0.6);
                                            }

                                            if ($noteFinale !== null) {
                                                $status = $noteFinale >= 10 ? 'pass' : 'fail';
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-gray-800 leading-none">{{ $ec->nom }}</p>
                                                <p class="text-[10px] text-gray-400 font-mono mt-1">{{ $ec->code_ec }}</p>
                                            </td>
                                            <td class="px-4 py-4 text-center font-black text-gray-400">{{ $ec->credit }}</td>
                                            
                                            <!-- CC -->
                                            <td class="px-4 py-4 text-center relative group">
                                                <span class="font-black text-blue-700">{{ $cc ? number_format($cc->valeur, 2) : '-' }}</span>
                                                @if(auth()->user()->isSuperAdmin() && $cc)
                                                    <button @click="openEdit({{ $cc->id }}, {{ $cc->valeur }}, 'CC', '{{ $ec->nom }}')" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 text-gray-300 hover:text-blue-600 transition-opacity">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                                    </button>
                                                @endif
                                            </td>

                                            <!-- SN -->
                                            <td class="px-4 py-4 text-center relative group">
                                                <span class="font-black text-blue-700">{{ $sn ? number_format($sn->valeur, 2) : '-' }}</span>
                                                @if(auth()->user()->isSuperAdmin() && $sn)
                                                    <button @click="openEdit({{ $sn->id }}, {{ $sn->valeur }}, 'SN', '{{ $ec->nom }}')" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 text-gray-300 hover:text-blue-600 transition-opacity">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                                    </button>
                                                @endif
                                            </td>

                                            <!-- RP -->
                                            <td class="px-4 py-4 text-center relative group">
                                                <span class="font-black text-red-700">{{ $rp ? number_format($rp->valeur, 2) : '-' }}</span>
                                                @if(auth()->user()->isSuperAdmin() && $rp)
                                                    <button @click="openEdit({{ $rp->id }}, {{ $rp->valeur }}, 'RP', '{{ $ec->nom }}')" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 text-gray-300 hover:text-red-600 transition-opacity">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                                    </button>
                                                @endif
                                            </td>

                                            <!-- Note Finale -->
                                            <td class="px-6 py-4 text-center">
                                                @if($noteFinale !== null)
                                                    <div class="inline-block px-4 py-1.5 rounded-xl font-black text-lg shadow-sm border-2 {{ $status == 'pass' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                                                        {{ number_format($noteFinale, 2) }}
                                                    </div>
                                                @else
                                                    <div class="inline-block px-4 py-1.5 rounded-xl font-black text-xs uppercase tracking-widest bg-orange-50 text-orange-600 border-2 border-orange-100">
                                                        Partiel
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white rounded-3xl p-20 text-center border-4 border-dashed border-gray-100">
            <div class="w-24 h-24 bg-gray-50 text-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h4 class="text-2xl font-black text-gray-300 uppercase tracking-tighter">Aucune note enregistrée</h4>
            <p class="text-gray-400 mt-2">Les notes saisies pour cet étudiant apparaîtront ici.</p>
            <a href="{{ route('notes.saisie', ['etudiant_id' => $etudiant->id]) }}" class="mt-8 inline-block px-8 py-3 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-200 hover:scale-105 transition-transform">Saisir maintenant</a>
        </div>
    @endforelse

    <!-- Modal Modification (Super Admin) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="showModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-[#1e3a8a] px-6 py-4 text-white flex justify-between items-center">
                <h4 class="font-black uppercase tracking-widest text-sm">Modifier Note · <span x-text="currentNote.type"></span></h4>
                <button @click="showModal = false" class="text-white/50 hover:text-white">&times;</button>
            </div>
            <form :action="`/notes/${currentNote.id}/modifier`" method="POST" class="p-8 space-y-6">
                @csrf
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Élément Constitutif</p>
                    <p class="text-lg font-black text-gray-800" x-text="currentNote.ec"></p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nouvelle Valeur (0-20)</label>
                    <input type="number" name="valeur" step="0.25" min="0" max="20" :value="currentNote.val" required class="w-full px-4 py-3 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none font-black text-2xl text-blue-700">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Motif de modification <span class="text-red-500">*</span></label>
                    <textarea name="motif_modification" required rows="3" class="w-full px-4 py-3 border-2 border-gray-100 rounded-2xl focus:border-blue-600 outline-none text-sm" placeholder="Expliquez pourquoi vous modifiez cette note..."></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-200 transition-all active:scale-95">
                    Enregistrer la modification
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
