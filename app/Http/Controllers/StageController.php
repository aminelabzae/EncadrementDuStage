<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\JournalStage;
use App\Models\ObjectifStage;
use App\Models\EvaluationStage;
use App\Models\Visite;
use App\Models\DocumentStage;
use App\Models\PresenceStage;
use App\Models\IncidentStage;
use App\Models\AttestationStage;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Stagiaire;
use App\Models\Entreprise;
use App\Models\Encadrant;

class StageController extends Controller
{
    public function index(Request $request)
    {
        $query = Stage::with(['entreprise', 'stagiaire.utilisateur', 'encadrant.utilisateur']);

        // Stagiaires can only see their own stages
        if (Auth::user()->hasRole('STAGIAIRE')) {
            $stagiaire = Auth::user()->stagiaire;
            if ($stagiaire) {
                $query->where('stagiaire_id', $stagiaire->id);
            } else {
                $query->whereRaw('1 = 0'); // no results if not linked to a stagiaire
            }
        } elseif (Auth::user()->hasRole('ENCADRANT')) {
            $encadrant = Auth::user()->encadrant;
            if ($encadrant) {
                $query->where('encadrant_id', $encadrant->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } else {
            // Admin or other roles - full access with filters
            if ($request->has('statut')) $query->where('statut', $request->statut);
            if ($request->has('date_debut')) $query->where('date_debut', '>=', $request->date_debut);
            if ($request->has('date_fin')) $query->where('date_fin', '<=', $request->date_fin);
            if ($request->has('entreprise_id')) $query->where('entreprise_id', $request->entreprise_id);
            if ($request->has('encadrant_id')) $query->where('encadrant_id', $request->encadrant_id);
            if ($request->has('stagiaire_id')) $query->where('stagiaire_id', $request->stagiaire_id);
        }

        $stages = $query->paginate($request->get('per_page', 15));

        return view('stages.index', compact('stages'));
    }

    public function create()
    {
        if (!Auth::user()->hasRole('ADMIN')) {
            abort(403, 'Seuls les administrateurs peuvent créer des stages.');
        }
        $entreprises = Entreprise::all();
        $stagiaires = Stagiaire::with('utilisateur')->get();
        $encadrants = Encadrant::with('utilisateur')->get();

        return view('stages.create', compact('entreprises', 'stagiaires', 'encadrants'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('ADMIN')) {
            abort(403, 'Seuls les administrateurs peuvent créer des stages.');
        }
        $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'stagiaire_id' => 'required|exists:stagiaires,id',
            'type' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'sujet' => 'required|string',
            'encadrant_id' => 'nullable|exists:encadrants,id',
        ]);

        $stage = Stage::create($request->all());

        JournalActivite::create([
            'utilisateur_id' => auth()->id(),
            'action' => 'create',
            'objet_type' => 'Stage',
            'objet_id' => $stage->id,
            'donnees_apres' => $stage->toArray(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('stages.index')->with('success', 'Stage créé avec succès.');
    }

    public function show(Stage $stage)
    {
        // Stagiaires can only view their own stage
        if (Auth::user()->hasRole('STAGIAIRE')) {
            $stagiaire = Auth::user()->stagiaire;
            if (!$stagiaire || $stage->stagiaire_id !== $stagiaire->id) {
                abort(403, 'Accès non autorisé.');
            }
        } elseif (Auth::user()->hasRole('ENCADRANT')) {
            $encadrant = Auth::user()->encadrant;
            if (!$encadrant || $stage->encadrant_id !== $encadrant->id) {
                abort(403, 'Accès non autorisé. Ce stage ne vous est pas affecté.');
            }
        }
        $stage->load([
            'entreprise.adresse',
            'stagiaire.utilisateur',
            'encadrant.utilisateur',
            'journalStages',
            'objectifs',
            'evaluations',
            'visites',
            'documents.pieceJointe',
            'presences',
            'incidents',
            'attestations.pieceJointe'
        ]);

        return view('stages.show', compact('stage'));
    }

    public function edit(Stage $stage)
    {
        if (Auth::user()->hasRole('ENCADRANT')) {
            $encadrant = Auth::user()->encadrant;
            if (!$encadrant || $stage->encadrant_id !== $encadrant->id) {
                abort(403, 'Accès non autorisé. Vous ne pouvez modifier que vos propres stages.');
            }
        } elseif (!Auth::user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        $entreprises = Entreprise::all();
        $stagiaires = Stagiaire::with('utilisateur')->get();
        $encadrants = Encadrant::with('utilisateur')->get();

        return view('stages.edit', compact('stage', 'entreprises', 'stagiaires', 'encadrants'));
    }

    public function update(Request $request, Stage $stage)
    {
        if (Auth::user()->hasRole('ENCADRANT')) {
            $encadrant = Auth::user()->encadrant;
            if (!$encadrant || $stage->encadrant_id !== $encadrant->id) {
                abort(403, 'Accès non autorisé. Vous ne pouvez modifier que vos propres stages.');
            }
        } elseif (!Auth::user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        $request->validate([
            'entreprise_id' => 'sometimes|exists:entreprises,id',
            'type' => 'sometimes|string|max:255',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'sujet' => 'sometimes|string',
            'statut' => 'sometimes|in:en_cours,termine,annule,suspendu',
            'encadrant_id' => 'nullable|exists:encadrants,id',
        ]);

        $oldData = $stage->toArray();
        $stage->update($request->all());

        JournalActivite::create([
            'utilisateur_id' => auth()->id(),
            'action' => 'update',
            'objet_type' => 'Stage',
            'objet_id' => $stage->id,
            'donnees_avant' => $oldData,
            'donnees_apres' => $stage->toArray(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('stages.index')->with('success', 'Stage mis à jour avec succès.');
    }

    public function destroy(Request $request, Stage $stage)
    {
        if (Auth::user()->hasRole('ENCADRANT')) {
            $encadrant = Auth::user()->encadrant;
            if (!$encadrant || $stage->encadrant_id !== $encadrant->id) {
                abort(403, 'Accès non autorisé. Vous ne pouvez supprimer que vos propres stages.');
            }
        } elseif (!Auth::user()->hasRole('ADMIN')) {
            abort(403, 'Accès non autorisé.');
        }
        JournalActivite::create([
            'utilisateur_id' => auth()->id(),
            'action' => 'delete',
            'objet_type' => 'Stage',
            'objet_id' => $stage->id,
            'donnees_avant' => $stage->toArray(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $stage->delete();

        return redirect()->route('stages.index')->with('success', 'Stage supprimé avec succès.');
    }

    // Journal entries
    public function addJournalEntry(Request $request, Stage $stage)
    {
        $request->validate([
            'date' => 'required|date',
            'activites' => 'required|string',
            'heures' => 'nullable|numeric|min:0|max:24',
        ]);

        $entry = $stage->journalStages()->create($request->all());

        return $this->successResponse($entry, 'Entrée journal ajoutée', 201);
    }

    // Objectives
    public function addObjective(Request $request, Stage $stage)
    {
        $request->validate([
            'libelle' => 'required|string',
            'statut' => 'sometimes|in:en_attente,en_cours,atteint,non_atteint',
        ]);

        $objective = $stage->objectifs()->create($request->all());

        return $this->successResponse($objective, 'Objectif ajouté', 201);
    }

    public function updateObjective(Request $request, ObjectifStage $objective)
    {
        $request->validate([
            'libelle' => 'sometimes|string',
            'statut' => 'sometimes|in:en_attente,en_cours,atteint,non_atteint',
        ]);

        $objective->update($request->all());

        return $this->successResponse($objective, 'Objectif mis à jour');
    }

    // Evaluations
    public function addEvaluation(Request $request, Stage $stage)
    {
        $request->validate([
            'note' => 'nullable|numeric|min:0|max:20',
            'commentaire' => 'nullable|string',
            'date_evaluation' => 'required|date',
        ]);

        $evaluation = $stage->evaluations()->create($request->all());

        return $this->successResponse($evaluation, 'Évaluation ajoutée', 201);
    }

    // Visits
    public function addVisite(Request $request, Stage $stage)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:presentiel,visio',
            'compte_rendu' => 'nullable|string',
        ]);

        $visite = $stage->visites()->create($request->all());

        return $this->successResponse($visite, 'Visite enregistrée', 201);
    }

    // Presence
    public function markPresence(Request $request, Stage $stage)
    {
        $request->validate([
            'date' => 'required|date',
            'present' => 'required|boolean',
        ]);

        $presence = $stage->presences()->updateOrCreate(
            ['date' => $request->date],
            ['present' => $request->present]
        );

        return $this->successResponse($presence, 'Présence enregistrée');
    }

  
    public function addIncident(Request $request, Stage $stage)
    {
        $request->validate([
            'description' => 'required|string',
            'date' => 'required|date',
            'gravite' => 'required|in:faible,moyenne,elevee,critique',
        ]);

        $incident = $stage->incidents()->create($request->all());

        return $this->successResponse($incident, 'Incident signalé', 201);
    }

    
    public function statistics(Request $request)
    {
        $stats = [
            'total' => Stage::count(),
            'en_cours' => Stage::where('statut', 'en_cours')->count(),
            'termine' => Stage::where('statut', 'termine')->count(),
            'annule' => Stage::where('statut', 'annule')->count(),
            'par_type' => Stage::select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get(),
            'par_entreprise' => Stage::select('entreprise_id', DB::raw('count(*) as total'))
                ->with('entreprise:id,nom')
                ->groupBy('entreprise_id')
                ->get(),
            'stages_actifs_ce_mois' => Stage::whereMonth('date_debut', now()->month)
                ->whereYear('date_debut', now()->year)
                ->count(),
        ];

        return $this->successResponse($stats);
    }
}