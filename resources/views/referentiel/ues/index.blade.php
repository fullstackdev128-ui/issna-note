@extends('layouts.app')

@section('title', 'Gestion des Unités d\'Enseignement')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">Unités d'Enseignement (UE)</h1>
            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Référentiel Académique</p>
        </div>
        
        <form action="{{ route('referentiel.ues.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <select name="specialite_id" class="bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-2 font-bold text-gray-700 outline-none focus:border-blue-500 transition-all text-xs">
                <option value="">Toutes les spécialités</option>
                @foreach($specialites as $s)
                    <option value="{{ $s->id }}" {{ request('specialite_id') == $s->id ? 'selected' : '' }}>{{ $s->nom }}</option>
                @endforeach
            </select>
            <select name="semestre" class="bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-2 font-bold text-gray-700 outline-none focus:border-blue-500 transition-all text-xs">
                <option value="">Tous les semestres</option>
                @foreach(range(1, 10) as $sem)
                    <option value="{{ $sem }}" {{ request('semestre') == $sem ? 'selected' : '' }}>Semestre {{ $sem }}</option>
                @endforeach
            </select>
            <button type="submit" class="p-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            <a href="{{ route('referentiel.ues.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-blue-100 flex items-center whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle UE
            </a>
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Code UE</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Désignation</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Niv. / Sem.</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">EC</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($ues as $u)
                <tr class="hover:bg-blue-50/30 transition-colors">
                    <td class="px-6 py-4">
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-lg font-black text-xs">{{ $u->code_ue }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-black text-gray-800 uppercase tracking-tight text-sm">{{ $u->nom }}</p>
                        <p class="text-[10px] text-blue-600 font-bold uppercase">{{ $u->specialite->nom }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $typeColor = [
                                'Fondamentale' => 'blue',
                                'Professionnelle' => 'indigo',
                                'Transversale' => 'purple'
                            ][$u->type_ue] ?? 'gray';
                        @endphp
                        <span class="bg-{{ $typeColor }}-50 text-{{ $typeColor }}-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-{{ $typeColor }}-100">
                            {{ $u->type_ue }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold text-gray-700">N{{ $u->niveau }}</span>
                        <span class="mx-1 text-gray-300">/</span>
                        <span class="font-bold text-blue-600">S{{ $u->semestre }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold text-gray-500">{{ $u->element_constitutifs_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('referentiel.ues.edit', $u) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('referentiel.ues.destroy', $u) }}" method="POST" onsubmit="return confirm('Supprimer cette UE ?');">
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
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Aucune UE trouvée pour ces filtres.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
