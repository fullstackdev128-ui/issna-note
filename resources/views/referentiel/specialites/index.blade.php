@extends('layouts.app')

@section('title', 'Gestion des Spécialités')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">Spécialités</h1>
            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Référentiel Académique</p>
        </div>
        
        <form action="{{ route('referentiel.specialites.index') }}" method="GET" class="flex items-center gap-4">
            <select name="filiere_id" onchange="this.form.submit()" class="bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-2 font-bold text-gray-700 outline-none focus:border-blue-500 transition-all text-xs">
                <option value="">Toutes les filières</option>
                @foreach($filieres as $f)
                    <option value="{{ $f->id }}" {{ request('filiere_id') == $f->id ? 'selected' : '' }}>{{ $f->nom }}</option>
                @endforeach
            </select>
            <a href="{{ route('referentiel.specialites.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-blue-100 flex items-center whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle Spécialité
            </a>
        </form>
    </div>

    @foreach($specialites as $filiereNom => $items)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
            <h3 class="text-xs font-black text-blue-600 uppercase tracking-widest">{{ $filiereNom }}</h3>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/30 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Code</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nom de la Spécialité</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Durée / Cycle</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">UE</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($items as $s)
                <tr class="hover:bg-blue-50/30 transition-colors">
                    <td class="px-6 py-4">
                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-lg font-black text-xs">{{ $s->code }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-black text-gray-800 uppercase tracking-tight">{{ $s->nom }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $cycle = $s->duree_ans == 2 ? 'BTS' : ($s->duree_ans == 3 ? 'Licence' : 'Master');
                            $color = $s->duree_ans == 2 ? 'amber' : ($s->duree_ans == 3 ? 'blue' : 'purple');
                        @endphp
                        <span class="bg-{{ $color }}-50 text-{{ $color }}-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-{{ $color }}-100">
                            {{ $cycle }} ({{ $s->duree_ans }} ans)
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold text-gray-500">{{ $s->unite_enseignements_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $s->actif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $s->actif ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('referentiel.specialites.edit', $s) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('referentiel.specialites.destroy', $s) }}" method="POST" onsubmit="return confirm('Supprimer cette spécialité ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</div>
@endsection
