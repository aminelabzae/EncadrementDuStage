<?php

namespace App\Http\Controllers;

use App\Models\EvaluationStage;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
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

    /**
     * Get the stage IDs that belong to the currently authenticated encadrant.
     */
    private function getEncadrantStageIds(): array
    {
        $user = Auth::user();
        $encadrant = $user->encadrant;
        if (!$encadrant) return [];
        return Stage::where('encadrant_id', $encadrant->id)->pluck('id')->toArray();
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('STAGIAIRE')) {
            $stageIds = $this->getStagiaireStageIds();
            $evaluations = EvaluationStage::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])
                ->whereIn('stage_id', $stageIds)
                ->latest()
                ->paginate(10);
        } elseif ($user->hasRole('ENCADRANT')) {
            $stageIds = $this->getEncadrantStageIds();
            $evaluations = EvaluationStage::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])
                ->whereIn('stage_id', $stageIds)
                ->latest()
                ->paginate(10);
        } else {
            $evaluations = EvaluationStage::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])->latest()->paginate(10);
        }
        return view('evaluations.index', compact('evaluations'));
    }

    public function create(Request $request)
    {
        // Only admins and encadrants can create evaluations
        if (Auth::user()->hasRole('STAGIAIRE')) {
            abort(403, 'Les stagiaires ne peuvent pas créer des évaluations.');
        }

        if (Auth::user()->hasRole('ENCADRANT')) {
            $stageIds = $this->getEncadrantStageIds();
            $stages = Stage::with('stagiaire.utilisateur')->whereIn('id', $stageIds)->get();
        } else {
            $stages = Stage::with('stagiaire.utilisateur')->get();
        }

        $selected_stage = $request->has('stage_id') ? Stage::find($request->stage_id) : null;
        return view('evaluations.create', compact('stages', 'selected_stage'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasRole('STAGIAIRE')) {
            abort(403, 'Les stagiaires ne peuvent pas créer des évaluations.');
        }

        if (Auth::user()->hasRole('ENCADRANT')) {
            $stageIds = $this->getEncadrantStageIds();
            if (!in_array($request->stage_id, $stageIds)) {
                abort(403, 'Vous ne pouvez évaluer que vos propres stagiaires.');
            }
        }
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'date_evaluation' => 'required|date',
            'note' => 'required|numeric|min:0|max:20',
            'commentaire' => 'nullable|string',
        ]);

        EvaluationStage::create($request->all());

        return redirect()->route('evaluations.index')->with('success', 'Évaluation ajoutée avec succès.');
    }

    public function show(EvaluationStage $evaluation)
    {
        // Stagiaires can only view their own evaluations
        if (Auth::user()->hasRole('STAGIAIRE')) {
            $stageIds = $this->getStagiaireStageIds();
            if (!in_array($evaluation->stage_id, $stageIds)) {
                abort(403, 'Accès non autorisé.');
            }
        }

        if (Auth::user()->hasRole('ENCADRANT')) {
            $stageIds = $this->getEncadrantStageIds();
            if (!in_array($evaluation->stage_id, $stageIds)) {
                abort(403, 'Vous ne pouvez consulter que les évaluations de vos propres stagiaires.');
            }
        }

        $evaluation->load(['stage.stagiaire.utilisateur', 'stage.entreprise', 'stage.encadrant.utilisateur']);
        return view('evaluations.show', compact('evaluation'));
    }

    public function edit(EvaluationStage $evaluation)
    {
        // Stagiaires cannot edit evaluations
        if (Auth::user()->hasRole('STAGIAIRE')) {
            abort(403, 'Les stagiaires ne peuvent pas modifier les évaluations.');
        }

        if (Auth::user()->hasRole('ENCADRANT')) {
            $stageIds = $this->getEncadrantStageIds();
            if (!in_array($evaluation->stage_id, $stageIds)) {
                abort(403, 'Vous ne pouvez modifier que les évaluations de vos propres stagiaires.');
            }
            $stages = Stage::with('stagiaire.utilisateur')->whereIn('id', $stageIds)->get();
        } else {
            $stages = Stage::with('stagiaire.utilisateur')->get();
        }

        return view('evaluations.edit', compact('evaluation', 'stages'));
    }

    public function update(Request $request, EvaluationStage $evaluation)
    {
        if (Auth::user()->hasRole('STAGIAIRE')) {
            abort(403, 'Les stagiaires ne peuvent pas modifier les évaluations.');
        }

        if (Auth::user()->hasRole('ENCADRANT')) {
            $stageIds = $this->getEncadrantStageIds();
            if (!in_array($evaluation->stage_id, $stageIds)) {
                abort(403, 'Vous ne pouvez modifier que les évaluations de vos propres stagiaires.');
            }
        }

        $request->validate([
            'date_evaluation' => 'required|date',
            'note' => 'required|numeric|min:0|max:20',
            'commentaire' => 'nullable|string',
        ]);

        $evaluation->update($request->all());

        return redirect()->route('evaluations.index')->with('success', 'Évaluation mise à jour.');
    }

    public function destroy(EvaluationStage $evaluation)
    {
        // Stagiaires cannot delete evaluations
        if (Auth::user()->hasRole('STAGIAIRE')) {
            abort(403, 'Les stagiaires ne peuvent pas supprimer les évaluations.');
        }

        if (Auth::user()->hasRole('ENCADRANT')) {
            $stageIds = $this->getEncadrantStageIds();
            if (!in_array($evaluation->stage_id, $stageIds)) {
                abort(403, 'Vous ne pouvez supprimer que les évaluations de vos propres stagiaires.');
            }
        }

        $evaluation->delete();
        return redirect()->route('evaluations.index')->with('success', 'Évaluation supprimée.');
    }
}
