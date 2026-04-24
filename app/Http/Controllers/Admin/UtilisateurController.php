<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UtilisateurController extends Controller
{
    public function index()
    {
        $users = User::orderBy('nom')->get();
        return view('referentiel.utilisateurs.index', compact('users'));
    }

    public function create()
    {
        return view('referentiel.utilisateurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'     => 'required|string|max:100',
            'prenoms' => 'nullable|string|max:150',
            'email'   => 'required|email|unique:users',
            'role'    => 'required|in:admin,super_admin',
        ]);
        $motDePasse = Str::random(10);
        User::create([
            'nom'      => $request->nom,
            'prenoms'  => $request->prenoms,
            'email'    => $request->email,
            'password' => Hash::make($motDePasse),
            'role'     => $request->role,
            'actif'    => true,
        ]);
        return redirect()->route('referentiel.utilisateurs.index')
            ->with('success', "Utilisateur créé. Mot de passe temporaire : {$motDePasse}");
    }

    public function edit(User $utilisateur)
    {
        return view('referentiel.utilisateurs.edit', compact('utilisateur'));
    }

    public function update(Request $request, User $utilisateur)
    {
        $request->validate([
            'nom'   => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$utilisateur->id,
            'role'  => 'required|in:admin,super_admin',
        ]);
        $data = $request->only(['nom', 'prenoms', 'email', 'role', 'actif']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $utilisateur->update($data);
        return redirect()->route('referentiel.utilisateurs.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $utilisateur)
    {
        $nbSuperAdmin = User::where('role', 'super_admin')->count();
        if ($utilisateur->role === 'super_admin' && $nbSuperAdmin <= 1) {
            return back()->with('error', 'Impossible : dernier super_admin.');
        }
        $utilisateur->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }
}
