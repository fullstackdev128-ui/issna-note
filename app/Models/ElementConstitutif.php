<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ElementConstitutif extends Model {
    protected $table = 'element_constitutifs';
    protected $fillable = ['ue_id', 'nom', 'credit', 'code_ec', 'note_eliminatoire'];
    protected $casts = ['note_eliminatoire' => 'decimal:2'];

    public function ue() { return $this->belongsTo(UniteEnseignement::class, 'ue_id'); }
    public function notes() { return $this->hasMany(Note::class, 'element_constitutif_id'); }
}
