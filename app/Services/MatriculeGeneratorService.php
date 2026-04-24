<?php
namespace App\Services;

use App\Models\Etudiant;
use App\Models\Specialite;
use App\Models\AnneeAcademique;

class MatriculeGeneratorService
{
    /**
     * Format réel ISSNA : [AA][CODE2][SEQ4]
     * Exemple : 24SI0024
     * AA    = 2 derniers chiffres de l'année de début (ex: 2024 → 24)
     * CODE2 = 2 premières lettres du code spécialité en majuscules
     * SEQ4  = numéro séquentiel 4 chiffres (0001, 0002...)
     */
    public function generer(Specialite $specialite, AnneeAcademique $annee): string
    {
        $aa = substr($annee->libelle, 2, 2); // "2024-2025" → "24"
        $code = strtoupper(substr($specialite->code, 0, 2)); // "SI" ou "IDE"

        // Dernier numéro séquentiel pour cette spécialité + année
        $dernierMatricule = Etudiant::where('specialite_id', $specialite->id)
            ->where('annee_acad_id', $annee->id)
            ->orderBy('matricule', 'desc')
            ->value('matricule');

        if ($dernierMatricule) {
            $dernierSeq = (int) substr($dernierMatricule, -4);
            $seq = $dernierSeq + 1;
        } else {
            $seq = 1;
        }

        return $aa . $code . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
