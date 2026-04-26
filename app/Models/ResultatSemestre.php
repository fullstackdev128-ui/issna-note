<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ResultatSemestre extends Model {
    protected $table = 'resultat_semestres';
    protected $fillable = [
        'etudiant_id', 'annee_acad_id', 'semestre',
        'total_credits', 'credits_valides', 'moyenne_sem',
        'mgp', 'grade', 'mention', 'decision_jury',
        'date_calcul', 'valide'
    ];
    protected $casts = [
        'moyenne_sem' => 'decimal:2',
        'mgp' => 'decimal:2',
        'date_calcul' => 'datetime',
        'valide' => 'boolean',
    ];

    public function etudiant() { return $this->belongsTo(Etudiant::class); }
    public function anneeAcademique() { return $this->belongsTo(AnneeAcademique::class, 'annee_acad_id'); }

    public function getDecisionJuryFormateeAttribute(): string {
        if (!$this->decision_jury) return '---';
        if ($this->decision_jury === 'À valider') return $this->decision_jury;
        
        return $this->decision_jury;
    }
}
