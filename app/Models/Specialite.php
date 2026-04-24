<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Specialite extends Model {
    protected $table = 'specialites';
    protected $fillable = ['filiere_id', 'code', 'nom', 'duree_ans', 'type_diplome', 'actif'];
    protected $casts = ['actif' => 'boolean'];

    public function filiere() { return $this->belongsTo(Filiere::class); }
    public function uniteEnseignements() { return $this->hasMany(UniteEnseignement::class); }
    public function etudiants() { return $this->hasMany(Etudiant::class); }

    // Nombre de semestres total selon durée
    public function getNbSemestresAttribute(): int {
        return $this->duree_ans * 2;
    }
}
