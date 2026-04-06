<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stagiaire;
use App\Models\Encadrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'mot_de_passe' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:stagiaire,encadrant,rh,admin',
            
            'ecole' => 'required_if:role,stagiaire|string|max:255',
            'filiere' => 'required_if:role,stagiaire|string|max:255',
            'niveau' => 'required_if:role,stagiaire|string|max:50',
            
            'entreprise_id' => 'required_if:role,encadrant|exists:entreprises,id',
            'poste' => 'required_if:role,encadrant|string|max:255',
        ]);

        $user = User::create([
            'uuid' => Str::uuid(),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'etat' => 'actif',
        ]);

        
        $role = \App\Models\Role::where('code', $request->role)->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        
        if ($request->role === 'stagiaire') {
            Stagiaire::create([
                'utilisateur_id' => $user->id,
                'ecole' => $request->ecole,
                'filiere' => $request->filiere,
                'niveau' => $request->niveau,
                'telephone' => $request->telephone,
            ]);
        } elseif ($request->role === 'encadrant') {
            Encadrant::create([
                'utilisateur_id' => $user->id,
                'entreprise_id' => $request->entreprise_id,
                'poste' => $request->poste,
                'telephone' => $request->telephone,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => $user->load('roles', 'stagiaire', 'encadrant'),
            'token' => $token
        ], 'Inscription réussie', 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->mot_de_passe])) {
            return $this->errorResponse('Email ou mot de passe incorrect', 401);
        }

        $user = User::with('roles', 'stagiaire', 'encadrant')->find(Auth::id());
        
        
        $user->derniere_connexion_at = now();
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'token' => $token
        ], 'Connexion réussie');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Déconnexion réussie');
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('roles', 'stagiaire', 'encadrant.entreprise', 'notifications');
        
        return $this->successResponse($user);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'photo_url' => 'nullable|url',
        ]);

        $user->update($request->only(['nom', 'prenom', 'telephone', 'photo_url']));

        return $this->successResponse($user, 'Profil mis à jour');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();
        $user->mot_de_passe = Hash::make($request->new_password);
        $user->save();

        return $this->successResponse(null, 'Mot de passe modifié avec succès');
    }
}