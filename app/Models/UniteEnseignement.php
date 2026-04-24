<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UniteEnseignement extends Model {
    protected $table = 'unite_enseignements';
    protected $fillable = ['specialite_id', 'code_ue', 'nom', 'type_ue', 'niveau', 'semestre'];

    public function specialite() { return $this->belongsTo(Specialite::class); }
    public function elementConstitutifs() { return $this->hasMany(ElementConstitutif::class, 'ue_id'); }

    // Crédit total de l'UE = somme des crédits de ses EC
    public function getCreditTotalAttribute(): int {
        return $this->elementConstitutifs->sum('credit');
    }
}
