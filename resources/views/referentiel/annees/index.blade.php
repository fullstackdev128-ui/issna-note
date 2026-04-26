@extends('layouts.app')

@section('title', 'Gestion des Années Académiques')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">Années Académiques</h1>
            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Référentiel Académique</p>
        </div>
        <a href="{{ route('referentiel.annees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-blue-100 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
            Nouvelle Année
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Libellé</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Début</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Fin</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($annees as $a)
                <tr class="hover:bg-blue-50/30 transition-colors {{ $a->active ? 'bg-blue-50/20' : '' }}">
                    <td class="px-6 py-4">
                        <p class="font-black text-gray-800 uppercase tracking-tight">{{ $a->libelle }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-bold">
                        {{ $a->date_debut ? $a->date_debut->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-bold">
                        {{ $a->date_fin ? $a->date_fin->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($a->active)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Active</span>
                        @else
                            <form action="{{ route('referentiel.annees.activer', $a) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-all">Activer</button>
                            </form>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('referentiel.annees.edit', $a) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('referentiel.annees.destroy', $a) }}" method="POST" onsubmit="return confirm('Supprimer cette année académique ?');">
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
</div>
@endsection
