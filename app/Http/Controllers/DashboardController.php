<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\Stagiaire;
use App\Models\Encadrant;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $data = [];

        if ($user->hasRole('admin')) {
            $data = $this->adminDashboard();
        } elseif ($user->hasRole('rh')) {
            $data = $this->rhDashboard();
        } elseif ($user->hasRole('encadrant')) {
            $data = $this->encadrantDashboard($user);
        } elseif ($user->hasRole('stagiaire')) {
            $data = $this->stagiaireDashboard($user);
        }

        return $this->successResponse($data);
    }

    private function adminDashboard()
    {
        return [
            'stats' => [
                'total_stages' => Stage::count(),
                'stages_en_cours' => Stage::where('statut', 'en_cours')->count(),
                'total_stagiaires' => Stagiaire::count(),
                'total_encadrants' => Encadrant::count(),
                'total_entreprises' => Entreprise::count(),
            ],
            'stages_par_mois' => Stage::select(
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('annee', 'mois')
            ->orderBy('annee')
            ->orderBy('mois')
            ->get(),
            'stages_par_statut' => Stage::select('statut', DB::raw('count(*) as total'))
                ->groupBy('statut')
                ->get(),
            'recent_stages' => Stage::with(['stagiaire.utilisateur', 'entreprise'])
                ->latest()
                ->take(10)
                ->get(),
            'prochaines_visites' => \App\Models\Visite::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])
                ->where('date', '>=', now())
                ->orderBy('date')
                ->take(10)
                ->get(),
        ];
    }

    private function rhDashboard()
    {
        return [
            'stats' => [
                'stages_en_cours' => Stage::where('statut', 'en_cours')->count(),
                'stages_a_venir' => Stage::where('date_debut', '>', now())->count(),
                'stages_termines' => Stage::where('statut', 'termine')->count(),
            ],
            'stages_par_entreprise' => Stage::select('entreprise_id', DB::raw('count(*) as total'))
                ->with('entreprise:id,nom')
                ->groupBy('entreprise_id')
                ->get(),
            'stages_par_type' => Stage::select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get(),
            'documents_en_attente' => \App\Models\DocumentStage::whereHas('stage', function($q) {
                    $q->where('statut', 'en_cours');
                })
                ->with(['stage.stagiaire.utilisateur'])
                ->latest()
                ->take(10)
                ->get(),
        ];
    }

    private function encadrantDashboard($user)
    {
        $encadrant = $user->encadrant;
        
        return [
            'stats' => [
                'stages_encadres' => Stage::where('encadrant_id', $encadrant->id)->count(),
                'stages_en_cours' => Stage::where('encadrant_id', $encadrant->id)
                    ->where('statut', 'en_cours')
                    ->count(),
                'stages_termines' => Stage::where('encadrant_id', $encadrant->id)
                    ->where('statut', 'termine')
                    ->count(),
            ],
            'stages_actifs' => Stage::where('encadrant_id', $encadrant->id)
                ->where('statut', 'en_cours')
                ->with(['stagiaire.utilisateur', 'entreprise'])
                ->get(),
            'prochaines_visites' => \App\Models\Visite::whereHas('stage', function($q) use ($encadrant) {
                    $q->where('encadrant_id', $encadrant->id);
                })
                ->where('date', '>=', now())
                ->with(['stage.stagiaire.utilisateur', 'stage.entreprise'])
                ->orderBy('date')
                ->take(5)
                ->get(),
            'derniers_journaux' => \App\Models\JournalStage::whereHas('stage', function($q) use ($encadrant) {
                    $q->where('encadrant_id', $encadrant->id);
                })
                ->with(['stage.stagiaire.utilisateur'])
                ->latest()
                ->take(10)
                ->get(),
        ];
    }

    private function stagiaireDashboard($user)
    {
        $stagiaire = $user->stagiaire;
        $currentStage = Stage::where('stagiaire_id', $stagiaire->id)
            ->where('statut', 'en_cours')
            ->with(['entreprise', 'encadrant.utilisateur'])
            ->first();

        return [
            'stage_actuel' => $currentStage,
            'historique_stages' => Stage::where('stagiaire_id', $stagiaire->id)
                ->where('statut', '!=', 'en_cours')
                ->with(['entreprise'])
                ->latest()
                ->take(5)
                ->get(),
            'statistiques' => [
                'total_heures' => \App\Models\JournalStage::whereHas('stage', function($q) use ($stagiaire) {
                        $q->where('stagiaire_id', $stagiaire->id);
                    })
                    ->sum('heures'),
                'objectifs_atteints' => \App\Models\ObjectifStage::whereHas('stage', function($q) use ($stagiaire) {
                        $q->where('stagiaire_id', $stagiaire->id);
                    })
                    ->where('statut', 'atteint')
                    ->count(),
                'total_presences' => \App\Models\PresenceStage::whereHas('stage', function($q) use ($stagiaire) {
                        $q->where('stagiaire_id', $stagiaire->id);
                    })
                    ->where('present', true)
                    ->count(),
            ],
            'dernieres_entrees_journal' => $currentStage ? 
                $currentStage->journalStages()->latest()->take(7)->get() : [],
            'prochaines_evaluations' => \App\Models\EvaluationStage::whereHas('stage', function($q) use ($stagiaire) {
                    $q->where('stagiaire_id', $stagiaire->id);
                })
                ->where('date_evaluation', '>=', now())
                ->with(['stage'])
                ->orderBy('date_evaluation')
                ->take(5)
                ->get(),
        ];
    }
}