@extends('layouts.app')

@section('title', "Fiche Étudiant : {$etudiant->nom_complet}")

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-blue-600 text-white rounded-xl shadow-lg">
                <span class="text-2xl font-black font-mono tracking-tighter">{{ $etudiant->matricule }}</span>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ $etudiant->nom_complet }}</h2>
                <p class="text-gray-500 font-medium">{{ $etudiant->specialite->nom }} · Niveau {{ $etudiant->niveau_actuel }}</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('etudiants.edit', $etudiant) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-white transition-colors flex items-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Modifier
            </a>
            <a href="{{ route('etudiants.index') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg font-semibold hover:bg-gray-900 transition-colors shadow-sm flex items-center">
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations Générales -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        Informations Personnelles
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Genre</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->genre == 'M' ? 'Masculin' : 'Féminin' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Date de naissance</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->date_naissance->format('d/m/Y') }} ({{ $etudiant->date_naissance->age }} ans)</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Lieu de naissance</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->lieu_naissance ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Téléphone</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->telephone ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase">Email</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->email ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase">Lieu de résidence</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->lieu_residence ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Parcours Académique
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Filière</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->specialite->filiere->nom }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Spécialité</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->specialite->nom }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Niveau Actuel</p>
                        <p class="text-gray-800 font-medium">Niveau {{ $etudiant->niveau_actuel }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Campus</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->campus->nom }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Année d'inscription</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->anneeAcademique->libelle }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Date d'inscription</p>
                        <p class="text-gray-800 font-medium">{{ $etudiant->date_inscription->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Fiche -->
        <div class="space-y-6">
            <!-- Statut -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs font-semibold text-gray-400 uppercase mb-3 tracking-wider">Statut Actuel</p>
                @php
                    $statusClasses = [
                        'actif' => 'bg-green-100 text-green-800 border-green-200',
                        'suspendu' => 'bg-orange-100 text-orange-800 border-orange-200',
                        'diplome' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'abandonne' => 'bg-red-100 text-red-800 border-red-200',
                    ];
                @endphp
                <div class="px-4 py-3 rounded-lg border text-center text-xl font-black uppercase tracking-widest {{ $statusClasses[$etudiant->statut] ?? 'bg-gray-100' }}">
                    {{ $etudiant->statut }}
                </div>
            </div>

            <!-- Parents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs font-semibold text-gray-400 uppercase mb-4 tracking-wider">Parent / Tuteur</p>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $etudiant->nom_parent ?? 'Non renseigné' }}</p>
                            <p class="text-xs text-gray-500">Nom complet</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 004.587 4.587l.773-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $etudiant->tel_parent ?? 'Non renseigné' }}</p>
                            <p class="text-xs text-gray-500">Téléphone</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes summary placeholder -->
            <div class="bg-gray-800 rounded-xl shadow-sm p-6 text-white">
                <p class="text-xs font-semibold text-gray-400 uppercase mb-4 tracking-wider">Récapitulatif Notes</p>
                <div class="text-center py-4 border-2 border-dashed border-gray-700 rounded-lg">
                    <p class="text-sm text-gray-400 italic">Aucune note pour le moment</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
