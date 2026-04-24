<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEtudiantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $etudiantId = $this->route('etudiant') ? $this->route('etudiant')->id : null;

        return [
            'nom' => 'required|string|max:100',
            'prenoms' => 'required|string|max:150',
            'date_naissance' => 'required|date|before:today',
            'lieu_naissance' => 'nullable|string|max:100',
            'genre' => 'required|in:M,F',
            'specialite_id' => 'required|exists:specialites,id',
            'campus_id' => 'required|exists:campus,id',
            'niveau_actuel' => 'required|integer|min:1|max:5',
            'email' => 'nullable|email|max:150|unique:etudiants,email,' . $etudiantId,
            'telephone' => 'nullable|string|max:20',
            'lieu_residence' => 'nullable|string|max:150',
            'etablissement_provenance' => 'nullable|string|max:200',
            'nom_parent' => 'nullable|string|max:200',
            'tel_parent' => 'nullable|string|max:20',
            'statut' => 'nullable|in:actif,suspendu,diplome,abandonne',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'prenoms.required' => 'Le prénom est obligatoire.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'date_naissance.before' => 'La date de naissance doit être une date passée.',
            'genre.required' => 'Le genre est obligatoire.',
            'specialite_id.required' => 'La spécialité est obligatoire.',
            'campus_id.required' => 'Le campus est obligatoire.',
            'niveau_actuel.required' => 'Le niveau actuel est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre étudiant.',
        ];
    }
}
