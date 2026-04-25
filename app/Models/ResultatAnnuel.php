<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultatAnnuel extends Model
{
    protected $table = 'resultat_annuels';

    protected $fillable = [ 
        'etudiant_id', 'annee_acad_id', 'niveau', 
        'moyenne_s1', 'moyenne_s2', 'moyenne_annuelle', 
        'credits_valides_s1', 'credits_valides_s2', 'credits_valides_total', 
        'total_credits', 'mgp_annuel', 'grade_annuel', 'mention_annuelle', 
        'decision_jury', 'valide_par', 'date_calcul', 
    ]; 
    
    protected $casts = [
        'date_calcul' => 'datetime',
        'moyenne_s1' => 'decimal:2',
        'moyenne_s2' => 'decimal:2',
        'moyenne_annuelle' => 'decimal:2',
        'mgp_annuel' => 'decimal:2',
    ]; 
    
    public function etudiant() { return $this->belongsTo(Etudiant::class); } 
    public function anneeAcademique() { return $this->belongsTo(AnneeAcademique::class, 'annee_acad_id'); } 
    public function validateurUser() { return $this->belongsTo(User::class, 'valide_par'); } 

    public function getDecisionJuryFormateeAttribute(): string {
        if (!$this->decision_jury) return '---';
        if ($this->decision_jury === 'À valider') return $this->decision_jury;
        
        $suffix = (isset($this->etudiant) && strtoupper($this->etudiant->genre) === 'F') ? 'e' : '';
        return str_replace('(e)', $suffix, $this->decision_jury);
    }
}
