<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = ['nom', 'prenoms', 'email', 'password', 'role', 'campus_id', 'actif'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['actif' => 'boolean', 'last_login' => 'datetime'];

    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function campus() { return $this->belongsTo(Campus::class); }

    public function getNomCompletAttribute(): string {
        return $this->nom . ($this->prenoms ? ' ' . $this->prenoms : '');
    }
}
