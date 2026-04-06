<?php

namespace App\Http\Controllers;

use App\Models\Stagiaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StagiaireController extends Controller
{
    public function index(Request $request)
    {
        $query = Stagiaire::with('utilisateur');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('utilisateur', function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->has('ecole') && !empty($request->ecole)) {
            $query->where('ecole', 'like', '%' . $request->ecole . '%');
        }

        if ($request->has('niveau') && !empty($request->niveau)) {
            $query->where('niveau', $request->niveau);
        }

        $stagiaires = $query->paginate($request->get('per_page', 10));

        return view('stagiaires.index', compact('stagiaires'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        return view('stagiaires.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'ecole' => 'required|string|max:255',
            'filiere' => 'required|string|max:255',
            'niveau' => 'required|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'uuid' => (string) Str::uuid(),
                'name' => $request->prenom . ' ' . $request->nom,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'password' => Hash::make(Str::random(12)),
                'mot_de_passe' => Hash::make(Str::random(12)),
                'etat' => 'actif',
            ]);

            // Assign stagiaire role
            try {
                $role = \App\Models\Role::where('code', 'STAGIAIRE')->first();
                if ($role) {
                    $user->roles()->attach($role->id);
                }
            } catch (\Exception $e) {}

            Stagiaire::create([
                'utilisateur_id' => $user->id,
                'ecole' => $request->ecole,
                'filiere' => $request->filiere,
                'niveau' => $request->niveau,
                'telephone' => $request->telephone,
            ]);

            DB::commit();
            return redirect()->route('stagiaires.index')->with('success', 'Stagiaire créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function show(Stagiaire $stagiaire)
    {
        $stagiaire->load(['utilisateur', 'stages' => function($q) {
            $q->with('entreprise', 'encadrant.utilisateur')->latest();
        }]);

        return view('stagiaires.show', compact('stagiaire'));
    }

    public function edit(Stagiaire $stagiaire)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        $stagiaire->load('utilisateur');
        return view('stagiaires.edit', compact('stagiaire'));
    }

    public function update(Request $request, Stagiaire $stagiaire)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $stagiaire->utilisateur_id,
            'ecole' => 'required|string|max:255',
            'filiere' => 'required|string|max:255',
            'niveau' => 'required|string|max:50',
            'telephone' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $stagiaire->utilisateur->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'name' => $request->prenom . ' ' . $request->nom,
                'email' => $request->email,
                'telephone' => $request->telephone,
            ]);

            $stagiaire->update([
                'ecole' => $request->ecole,
                'filiere' => $request->filiere,
                'niveau' => $request->niveau,
                'telephone' => $request->telephone,
            ]);

            DB::commit();
            return redirect()->route('stagiaires.index')->with('success', 'Stagiaire mis à jour');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour');
        }
    }

    public function destroy(Stagiaire $stagiaire)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        DB::beginTransaction();
        try {
            $user = $stagiaire->utilisateur;
            $stagiaire->delete();
            if ($user) $user->delete();
            DB::commit();
            return redirect()->route('stagiaires.index')->with('success', 'Stagiaire supprimé');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression');
        }
    }
}