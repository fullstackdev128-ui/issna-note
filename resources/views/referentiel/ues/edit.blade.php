@extends('layouts.app')

@section('title', 'Modifier l\'Unité d\'Enseignement (UE)')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <a href="{{ route('referentiel.ues.index') }}" class="p-2 bg-gray-50 text-gray-400 hover:text-blue-600 rounded-xl transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">Modifier : {{ $ue->nom }}</h1>
                <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Référentiel Académique</p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
            <ul class="list-disc list-inside text-red-700 text-xs font-bold uppercase tracking-widest">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('referentiel.ues.update', $ue) }}" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Spécialité</label>
                <select name="specialite_id" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    @foreach($specialites as $s)
                        <option value="{{ $s->id }}" {{ old('specialite_id', $ue->specialite_id) == $s->id ? 'selected' : '' }}>
                            {{ $s->filiere->code }} - {{ $s->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Code UE</label>
                    <input type="text" name="code_ue" value="{{ old('code_ue', $ue->code_ue) }}" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Type d'UE</label>
                    <select name="type_ue" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                        <option value="Fondamentale" {{ old('type_ue', $ue->type_ue) == 'Fondamentale' ? 'selected' : '' }}>Fondamentale</option>
                        <option value="Professionnelle" {{ old('type_ue', $ue->type_ue) == 'Professionnelle' ? 'selected' : '' }}>Professionnelle</option>
                        <option value="Transversale" {{ old('type_ue', $ue->type_ue) == 'Transversale' ? 'selected' : '' }}>Transversale</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Désignation de l'UE</label>
                <input type="text" name="nom" value="{{ old('nom', $ue->nom) }}" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Niveau</label>
                    <input type="number" name="niveau" value="{{ old('niveau', $ue->niveau) }}" min="1" max="5" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Semestre</label>
                    <input type="number" name="semestre" value="{{ old('semestre', $ue->semestre) }}" min="1" max="2" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>
        </div>

        <div class="flex pt-4 space-x-4">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-blue-100">
                Mettre à jour l'UE
            </button>
            <a href="{{ route('referentiel.ues.index') }}" class="px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs text-gray-400 hover:bg-gray-50 transition-all text-center">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
