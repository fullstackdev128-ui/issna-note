<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model {
    protected $table = 'etudiants';
    protected $fillable = [
        'matricule', 'nom', 'prenoms', 'date_naissance', 'lieu_naissance',
        'genre', 'telephone', 'email', 'lieu_residence',
        'etablissement_provenance', 'nom_parent', 'tel_parent',
        'campus_id', 'specialite_id', 'niveau_actuel',
        'annee_acad_id', 'date_inscription', 'statut'
    ];
    protected $casts = ['date_naissance' => 'date', 'date_inscription' => 'date'];

    public function campus() { return $this->belongsTo(Campus::class); }
    public function specialite() { return $this->belongsTo(Specialite::class); }
    public function anneeAcademique() { return $this->belongsTo(AnneeAcademique::class, 'annee_acad_id'); }
    public function notes() { return $this->hasMany(Note::class); }
    public function resultatsSemestres() { return $this->hasMany(ResultatSemestre::class); }

    public function getNomCompletAttribute(): string {
        return strtoupper($this->nom) . ' ' . $this->prenoms;
    }
}
