<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Note extends Model {
    protected $table = 'notes';
    protected $fillable = [
        'etudiant_id', 'element_constitutif_id', 'annee_acad_id',
        'semestre', 'type_examen', 'valeur', 'date_saisie',
        'saisi_par', 'modifie_par', 'date_modification', 'motif_modification'
    ];
    protected $casts = [
        'valeur' => 'decimal:2',
        'date_saisie' => 'datetime',
        'date_modification' => 'datetime',
    ];

    public function etudiant() { return $this->belongsTo(Etudiant::class); }
    public function elementConstitutif() { return $this->belongsTo(ElementConstitutif::class, 'element_constitutif_id'); }
    public function anneeAcademique() { return $this->belongsTo(AnneeAcademique::class, 'annee_acad_id'); }
    public function saisiPar() { return $this->belongsTo(User::class, 'saisi_par'); }
    public function modifiePar() { return $this->belongsTo(User::class, 'modifie_par'); }
}
