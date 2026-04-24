<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AnneeAcademique extends Model {
    protected $table = 'annee_academiques';
    protected $fillable = ['libelle', 'date_debut', 'date_fin', 'active'];
    protected $casts = ['active' => 'boolean', 'date_debut' => 'date', 'date_fin' => 'date'];

    public function etudiants() { return $this->hasMany(Etudiant::class, 'annee_acad_id'); }
    public function notes() { return $this->hasMany(Note::class, 'annee_acad_id'); }

    public static function active() {
        return static::where('active', true)->firstOrFail();
    }
}
