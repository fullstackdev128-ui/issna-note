@extends('layouts.app')

@section('title', 'Gestion des Matières (EC)')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">Éléments Constitutifs (EC)</h1>
            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Référentiel Académique</p>
        </div>
        @if($ueId)
        <a href="{{ route('referentiel.ecs.create', ['ue_id' => $ueId]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-blue-100 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
            Nouvelle Matière
        </a>
        @endif
    </div>

    <!-- Filtres -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <form action="{{ route('referentiel.ecs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Spécialité</label>
                <select name="specialite_id" onchange="this.form.submit()" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="">Choisir une spécialité...</option>
                    @foreach($specialites as $s)
                        <option value="{{ $s->id }}" {{ $specialiteId == $s->id ? 'selected' : '' }}>
                            {{ $s->filiere->code }} - {{ $s->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Unité d'Enseignement (UE)</label>
                <select name="ue_id" onchange="this.form.submit()" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="">Choisir une UE...</option>
                    @foreach($ues as $ue)
                        <option value="{{ $ue->id }}" {{ $ueId == $ue->id ? 'selected' : '' }}>
                            [S{{ $ue->semestre }}] {{ $ue->nom }} ({{ $ue->code_ue }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('referentiel.ecs.index') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600 underline">Réinitialiser</a>
            </div>
        </form>
    </div>

    @if($ueId)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Code</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nom de la Matière</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Crédits</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Note Elim.</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($ecs as $ec)
                <tr class="hover:bg-blue-50/30 transition-colors">
                    <td class="px-6 py-4">
                        <span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-lg font-black text-xs">{{ $ec->code_ec ?: 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-black text-gray-800 uppercase tracking-tight">{{ $ec->nom }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-black text-blue-600">{{ $ec->credit }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold text-red-500">{{ $ec->note_eliminatoire ?: '0.00' }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('referentiel.ecs.edit', $ec) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('referentiel.ecs.destroy', $ec) }}" method="POST" onsubmit="return confirm('Supprimer cette matière ?');">
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
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">
                        Aucune matière trouvée pour cette UE.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @else
    <div class="bg-white p-12 rounded-3xl border border-dashed border-gray-200 text-center">
        <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Veuillez sélectionner une spécialité et une UE pour afficher les matières.</p>
    </div>
    @endif
</div>
@endsection
