<?php

namespace App\Http\Controllers;

use App\Models\Encadrant;
use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EncadrantController extends Controller
{
    public function index(Request $request)
    {
        $query = Encadrant::with(['utilisateur', 'entreprise']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('utilisateur', function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->has('entreprise_id') && !empty($request->entreprise_id)) {
            $query->where('entreprise_id', $request->entreprise_id);
        }

        $encadrants = $query->paginate($request->get('per_page', 10));
        $entreprises = Entreprise::all();

        return view('encadrants.index', compact('encadrants', 'entreprises'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        $entreprises = Entreprise::all();
        return view('encadrants.create', compact('entreprises'));
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
            'entreprise_id' => 'required|exists:entreprises,id',
            'poste' => 'required|string|max:255',
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


            try {
                $role = \App\Models\Role::where('code', 'encadrant')->first();
                if ($role) {
                    $user->roles()->attach($role->id);
                }
            } catch (\Exception $e) {
                
            }

            Encadrant::create([
                'utilisateur_id' => $user->id,
                'entreprise_id' => $request->entreprise_id,
                'poste' => $request->poste,
                'telephone' => $request->telephone,
            ]);

            DB::commit();
            return redirect()->route('encadrants.index')->with('success', 'Encadrant créé avec succès. Un mot de passe aléatoire a été généré.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function show(Encadrant $encadrant)
    {
        $encadrant->load(['utilisateur', 'entreprise', 'stages.stagiaire.utilisateur']);
        return view('encadrants.show', compact('encadrant'));
    }

    public function edit(Encadrant $encadrant)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        $encadrant->load('utilisateur');
        $entreprises = Entreprise::all();
        return view('encadrants.edit', compact('encadrant', 'entreprises'));
    }

    public function update(Request $request, Encadrant $encadrant)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $encadrant->utilisateur_id,
            'poste' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'entreprise_id' => 'required|exists:entreprises,id',
        ]);

        DB::beginTransaction();
        try {
            $encadrant->utilisateur->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'name' => $request->prenom . ' ' . $request->nom,
                'email' => $request->email,
                'telephone' => $request->telephone,
            ]);

            $encadrant->update([
                'poste' => $request->poste,
                'telephone' => $request->telephone,
                'entreprise_id' => $request->entreprise_id,
            ]);

            DB::commit();
            return redirect()->route('encadrants.index')->with('success', 'Encadrant mis à jour');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy(Encadrant $encadrant)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        DB::beginTransaction();
        try {
            $user = $encadrant->utilisateur;
            $encadrant->delete();
            if ($user) $user->delete();
            DB::commit();
            return redirect()->route('encadrants.index')->with('success', 'Encadrant supprimé');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression');
        }
    }
}