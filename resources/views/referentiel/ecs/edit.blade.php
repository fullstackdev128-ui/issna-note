@extends('layouts.app')

@section('title', 'Modifier la Matière')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">Modifier : {{ $ec->nom }}</h1>
        <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Référentiel Académique</p>
    </div>

    <form action="{{ route('referentiel.ecs.update', $ec) }}" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Unité d'Enseignement (UE)</label>
                <select name="ue_id" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    @foreach($ues as $ue)
                        <option value="{{ $ue->id }}" {{ old('ue_id', $ec->ue_id) == $ue->id ? 'selected' : '' }}>
                            {{ $ue->nom }} ({{ $ue->code_ue }}) - {{ $ue->specialite->nom }}
                        </option>
                    @endforeach
                </select>
                @error('ue_id') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Code EC</label>
                    <input type="text" name="code_ec" value="{{ old('code_ec', $ec->code_ec) }}" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('code_ec') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Crédits</label>
                    <input type="number" name="credit" value="{{ old('credit', $ec->credit) }}" min="1" max="10" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('credit') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nom de la Matière</label>
                <input type="text" name="nom" value="{{ old('nom', $ec->nom) }}" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                @error('nom') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Note Éliminatoire (Optionnel)</label>
                <input type="number" step="0.25" name="note_eliminatoire" value="{{ old('note_eliminatoire', $ec->note_eliminatoire) }}" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                @error('note_eliminatoire') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex pt-4 space-x-4">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-blue-100">
                Mettre à jour la matière
            </button>
            <a href="{{ route('referentiel.ecs.index', ['ue_id' => $ec->ue_id]) }}" class="px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs text-gray-400 hover:bg-gray-50 transition-all text-center">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
