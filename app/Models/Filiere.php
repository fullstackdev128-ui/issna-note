<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model {
    protected $table = 'filieres';
    protected $fillable = ['code', 'nom', 'actif'];
    protected $casts = ['actif' => 'boolean'];

    public function specialites() { return $this->hasMany(Specialite::class); }
}
