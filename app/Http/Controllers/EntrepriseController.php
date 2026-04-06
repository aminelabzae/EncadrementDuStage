<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Adresse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntrepriseController extends Controller
{
    public function index(Request $request)
    {
        $query = Entreprise::with('adresse');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        $entreprises = $query->paginate($request->get('per_page', 10));

        return view('entreprises.index', compact('entreprises'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Seuls les administrateurs peuvent créer des entreprises.');
        }
        return view('entreprises.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Seuls les administrateurs peuvent créer des entreprises.');
        }
        $request->validate([
            'nom' => 'required|string|max:255',
            'pays' => 'required|string|max:100',
            'ville' => 'required|string|max:100',
            'quartier' => 'nullable|string|max:255',
            'rue' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $adresse = Adresse::create([
                'pays' => $request->pays,
                'ville' => $request->ville,
                'quartier' => $request->quartier,
                'rue' => $request->rue,
                'code_postal' => $request->code_postal,
            ]);

            Entreprise::create([
                'nom' => $request->nom,
                'adresse_id' => $adresse->id,
            ]);

            DB::commit();
            return redirect()->route('entreprises.index')->with('success', 'Entreprise créée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function show(Entreprise $entreprise)
    {
        $entreprise->load(['adresse', 'stages.stagiaire.utilisateur', 'encadrants.utilisateur']);

        return view('entreprises.show', compact('entreprise'));
    }

    public function edit(Entreprise $entreprise)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Seuls les administrateurs peuvent modifier les entreprises.');
        }
        $entreprise->load('adresse');
        return view('entreprises.edit', compact('entreprise'));
    }

    public function update(Request $request, Entreprise $entreprise)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Seuls les administrateurs peuvent modifier les entreprises.');
        }
        $request->validate([
            'nom' => 'required|string|max:255',
            'pays' => 'required|string|max:100',
            'ville' => 'required|string|max:100',
            'quartier' => 'nullable|string|max:255',
            'rue' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $entreprise->update(['nom' => $request->nom]);

            if ($entreprise->adresse) {
                $entreprise->adresse->update([
                    'pays' => $request->pays,
                    'ville' => $request->ville,
                    'quartier' => $request->quartier,
                    'rue' => $request->rue,
                    'code_postal' => $request->code_postal,
                ]);
            } else {
                $adresse = Adresse::create([
                    'pays' => $request->pays,
                    'ville' => $request->ville,
                    'quartier' => $request->quartier,
                    'rue' => $request->rue,
                    'code_postal' => $request->code_postal,
                ]);
                $entreprise->update(['adresse_id' => $adresse->id]);
            }

            DB::commit();
            return redirect()->route('entreprises.index')->with('success', 'Entreprise mise à jour');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy(Entreprise $entreprise)
    {
        if (!auth()->user()->hasRole('ADMIN')) {
            abort(403, 'Seuls les administrateurs peuvent supprimer les entreprises.');
        }
        $entreprise->delete();
        return redirect()->route('entreprises.index')->with('success', 'Entreprise supprimée');
    }
}