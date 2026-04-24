@extends('layouts.app')

@section('title', 'Nouvel Utilisateur')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">Ajouter un Utilisateur</h1>
        <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Administration Système</p>
    </div>

    <form action="{{ route('referentiel.utilisateurs.store') }}" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
        @csrf
        
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex: DOE" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('nom') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Prénoms</label>
                    <input type="text" name="prenoms" value="{{ old('prenoms') }}" placeholder="Ex: John" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('prenoms') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Ex: john.doe@issna.com" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Rôle</label>
                <select name="role" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Administrateur</option>
                </select>
                @error('role') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="bg-blue-50 p-6 rounded-2xl">
            <p class="text-xs text-blue-600 font-bold uppercase tracking-widest">
                Note : Un mot de passe temporaire sera généré automatiquement et affiché après la création.
            </p>
        </div>

        <div class="flex pt-4 space-x-4">
            <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-red-100">
                Créer l'utilisateur
            </button>
            <a href="{{ route('referentiel.utilisateurs.index') }}" class="px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-xs text-gray-400 hover:bg-gray-50 transition-all text-center">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
