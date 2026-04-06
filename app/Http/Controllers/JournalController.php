<?php

namespace App\Http\Controllers;

use App\Models\JournalStage;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    /**
     * Get the stage IDs that belong to the currently authenticated stagiaire.
     */
    private function getStagiaireStageIds(): array
    {
        $user = Auth::user();
        $stagiaire = $user->stagiaire;
        if (!$stagiaire) return [];
        return Stage::where('stagiaire_id', $stagiaire->id)->pluck('id')->toArray();
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('STAGIAIRE')) {
            $stageIds = $this->getStagiaireStageIds();
            $journaux = JournalStage::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])
                ->whereIn('stage_id', $stageIds)
                ->latest()
                ->paginate(15);
        } else {
            $journaux = JournalStage::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])->latest()->paginate(15);
        }
        return view('journaux.index', compact('journaux'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('STAGIAIRE')) {
            $stageIds = $this->getStagiaireStageIds();
            $stages = Stage::with('stagiaire.utilisateur')->whereIn('id', $stageIds)->get();
        } else {
            $stages = Stage::with('stagiaire.utilisateur')->get();
        }
        $selected_stage = $request->has('stage_id') ? Stage::find($request->stage_id) : null;
        return view('journaux.create', compact('stages', 'selected_stage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'date' => [
                'required',
                'date',
                \Illuminate\Validation\Rule::unique('journal_stages')->where(fn ($q) => $q->where('stage_id', $request->stage_id)),
            ],
            'activites' => 'required|string',
            'heures' => 'nullable|numeric|min:0|max:24',
        ], [
            'date.unique' => 'Une entrée de journal existe déjà pour ce stage à cette date. Veuillez choisir une autre date.',
        ]);

        // Stagiaires can only create journals for their own stage
        if (Auth::user()->hasRole('STAGIAIRE')) {
            $stageIds = $this->getStagiaireStageIds();
            if (!in_array($request->stage_id, $stageIds)) {
                abort(403, 'Vous ne pouvez pas créer un journal pour un autre stagiaire.');
            }
        }

        JournalStage::create($request->all());

        return redirect()->route('journaux.index')->with('success', 'Entrée de journal ajoutée.');
    }

    public function show(JournalStage $journal)
    {
        // Stagiaires can only view their own
        if (Auth::user()->hasRole('STAGIAIRE')) {
            $stageIds = $this->getStagiaireStageIds();
            if (!in_array($journal->stage_id, $stageIds)) {
                abort(403, 'Accès non autorisé.');
            }
        }
        $journal->load(['stage.stagiaire.utilisateur', 'stage.entreprise']);
        return view('journaux.show', compact('journal'));
    }

    public function edit(JournalStage $journal)
    {
        // Stagiaires can only edit their own
        if (Auth::user()->hasRole('STAGIAIRE')) {
            $stageIds = $this->getStagiaireStageIds();
            if (!in_array($journal->stage_id, $stageIds)) {
                abort(403, 'Vous ne pouvez modifier que vos propres journaux.');
            }
            $stages = Stage::with('stagiaire.utilisateur')->whereIn('id', $stageIds)->get();
        } else {
            $stages = Stage::with('stagiaire.utilisateur')->get();
        }
        return view('journaux.edit', compact('journal', 'stages'));
    }

    public function update(Request $request, JournalStage $journal)
    {
        $request->validate([
            'date' => [
                'required',
                'date',
                \Illuminate\Validation\Rule::unique('journal_stages')->where(fn ($q) => $q->where('stage_id', $journal->stage_id))->ignore($journal->id),
            ],
            'activites' => 'required|string',
            'heures' => 'nullable|numeric|min:0|max:24',
        ], [
            'date.unique' => 'Une entrée de journal existe déjà pour ce stage à cette date.',
        ]);

        // Stagiaires can only update their own
        if (Auth::user()->hasRole('STAGIAIRE')) {
            $stageIds = $this->getStagiaireStageIds();
            if (!in_array($journal->stage_id, $stageIds)) {
                abort(403, 'Vous ne pouvez modifier que vos propres journaux.');
            }
        }

        $journal->update($request->all());

        return redirect()->route('journaux.index')->with('success', 'Journal mis à jour.');
    }

    public function destroy(JournalStage $journal)
    {
        // Stagiaires cannot delete journals
        if (Auth::user()->hasRole('STAGIAIRE')) {
            abort(403, 'Les stagiaires ne peuvent pas supprimer les journaux.');
        }
        $journal->delete();
        return redirect()->route('journaux.index')->with('success', 'Journal supprimé.');
    }
}
