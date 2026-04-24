<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model {
    protected $table = 'campus';
    protected $fillable = ['nom', 'ville', 'adresse', 'telephone'];

    public function etudiants() { return $this->hasMany(Etudiant::class); }
    public function users() { return $this->hasMany(User::class); }
}
