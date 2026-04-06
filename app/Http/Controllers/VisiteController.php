<?php

namespace App\Http\Controllers;

use App\Models\Visite;
use App\Models\Stage;
use Illuminate\Http\Request;

class VisiteController extends Controller
{
    public function index()
    {
        // Show only upcoming visits (date >= today)
        $visites = Visite::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->paginate(10);
        return view('visites.index', compact('visites'));
    }

    public function create(Request $request)
    {
        $stages = Stage::with('stagiaire.utilisateur')->get();
        $selected_stage = $request->has('stage_id') ? Stage::find($request->stage_id) : null;
        return view('visites.create', compact('stages', 'selected_stage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'date' => 'required|date',
            'type' => 'required|string',
            'compte_rendu' => 'nullable|string',
        ]);

        Visite::create($request->all());

        return redirect()->route('visites.index')->with('success', 'Visite planifiée avec succès.');
    }

    public function show(Visite $visite)
    {
        $visite->load(['stage.stagiaire.utilisateur', 'stage.entreprise', 'stage.encadrant.utilisateur']);
        return view('visites.show', compact('visite'));
    }

    public function edit(Visite $visite)
    {
        $stages = Stage::with('stagiaire.utilisateur')->get();
        return view('visites.edit', compact('visite', 'stages'));
    }

    public function update(Request $request, Visite $visite)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|string',
            'compte_rendu' => 'nullable|string',
        ]);

        $visite->update($request->all());

        return redirect()->route('visites.index')->with('success', 'Visite mise à jour.');
    }

    public function destroy(Visite $visite)
    {
        $visite->delete();
        return redirect()->route('visites.index')->with('success', 'Visite supprimée.');
    }
}
