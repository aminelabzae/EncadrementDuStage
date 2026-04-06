<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

// ─── Public ───────────────────────────────────────────────────────────────────

Route::redirect('/', '/login');

// ─── Auth ─────────────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// ─── Protected ────────────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $data = [
            'current_year' => (date('m') >= 9) ? date('Y').'-'.(date('Y')+1) : (date('Y')-1).'-'.date('Y'),
        ];

        if ($user->hasRole('STAGIAIRE')) {
            $stagiaire = $user->stagiaire;
            $myStage = $stagiaire
                ? \App\Models\Stage::with(['entreprise', 'encadrant.utilisateur', 'evaluations', 'journalStages'])
                    ->where('stagiaire_id', $stagiaire->id)
                    ->latest()
                    ->first()
                : null;

            $myJournauxCount = $myStage ? $myStage->journalStages->count() : 0;
            $myEvalAvg       = $myStage && $myStage->evaluations->count()
                ? round($myStage->evaluations->avg('note'), 2)
                : null;
            $myEvalCount     = $myStage ? $myStage->evaluations->count() : 0;

            // Pre-compute all display strings so Blade stays simple
            $myStatut    = $myStage ? ucfirst(str_replace('_', ' ', $myStage->statut ?? 'N/A')) : 'N/A';
            $myEvalLabel = $myEvalAvg !== null ? $myEvalAvg.'/20' : '—';

            if ($myStage && $myStage->date_fin) {
                $diff = (int) now()->diffInDays(\Carbon\Carbon::parse($myStage->date_fin), false);
                $myDaysLeft = $diff >= 0 ? $diff.' j.' : abs($diff).' j. passé';
            } else {
                $myDaysLeft = '—';
            }

            $myStageRows = $myStage ? [
                ['Sujet',      $myStage->sujet ?? 'N/A'],
                ['Entreprise', $myStage->entreprise->nom ?? 'N/A'],
                ['Encadrant',  $myStage->encadrant?->utilisateur?->name ?? 'Non assigné'],
                ['Début',      $myStage->date_debut ? \Carbon\Carbon::parse($myStage->date_debut)->format('d/m/Y') : '?'],
                ['Fin',        $myStage->date_fin   ? \Carbon\Carbon::parse($myStage->date_fin)->format('d/m/Y')   : 'À définir'],
                ['Type',       $myStage->type ?? 'N/A'],
            ] : [];

            $data['my_stage']          = $myStage;
            $data['my_journaux_count'] = $myJournauxCount;
            $data['my_eval_avg']       = $myEvalAvg;
            $data['my_eval_count']     = $myEvalCount;
            $data['my_statut']         = $myStatut;
            $data['my_eval_label']     = $myEvalLabel;
            $data['my_days_left']      = $myDaysLeft;
            $data['my_stage_rows']     = $myStageRows;
        } elseif ($user->hasRole('ENCADRANT')) {
            $encadrant = $user->encadrant;
            if ($encadrant) {
                $myStages = \App\Models\Stage::with(['stagiaire.utilisateur', 'entreprise', 'evaluations', 'journalStages'])
                    ->where('encadrant_id', $encadrant->id)
                    ->get();
                
                $data['my_stagiaires_count'] = $myStages->pluck('stagiaire_id')->unique()->count();
                $data['my_active_stages_count'] = $myStages->where('statut', 'en_cours')->count();
                $data['my_evaluations_count'] = \App\Models\EvaluationStage::whereIn('stage_id', $myStages->pluck('id'))->count();
                $data['my_upcoming_visites_count'] = \App\Models\Visite::whereIn('stage_id', $myStages->pluck('id'))
                    ->whereDate('date', '>=', now()->toDateString())
                    ->count();
                
                $data['my_recent_journaux'] = \App\Models\JournalStage::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])
                    ->whereIn('stage_id', $myStages->pluck('id'))
                    ->latest()
                    ->take(5)
                    ->get();
                
                $data['my_upcoming_visites'] = \App\Models\Visite::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])
                    ->whereIn('stage_id', $myStages->pluck('id'))
                    ->whereDate('date', '>=', now()->toDateString())
                    ->orderBy('date', 'asc')
                    ->take(3)
                    ->get();
            }
            $data['is_encadrant'] = true;
        } else {
            $data['stagiaires_count']  = \App\Models\Stagiaire::count();
            $data['stages_count']      = \App\Models\Stage::where('statut', 'en_cours')->count();
            $data['encadrants_count']  = \App\Models\Encadrant::count();
            $data['entreprises_count'] = \App\Models\Entreprise::count();
            $data['evaluations_count'] = \App\Models\EvaluationStage::count();
            $data['visites_count']     = \App\Models\Visite::whereDate('date', '>=', now()->toDateString())->count();
            $data['recent_journaux']   = \App\Models\JournalStage::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])->latest()->take(5)->get();
            $data['upcoming_visites']  = \App\Models\Visite::with(['stage.stagiaire.utilisateur', 'stage.entreprise'])->whereDate('date', '>=', now()->toDateString())->orderBy('date', 'asc')->take(3)->get();
        }

        return view('dashboard', $data);
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Stages (CRUD)
    Route::resource('stages', \App\Http\Controllers\StageController::class);

    // Entreprises (CRUD)
    Route::resource('entreprises', \App\Http\Controllers\EntrepriseController::class);

    // Encadrants (CRUD)
    Route::resource('encadrants', \App\Http\Controllers\EncadrantController::class);

    // Stagiaires (CRUD)
    Route::resource('stagiaires', \App\Http\Controllers\StagiaireController::class);

    // Follow-up resources
    Route::resource('evaluations', \App\Http\Controllers\EvaluationController::class);
    Route::resource('visites', \App\Http\Controllers\VisiteController::class);
    Route::resource('journaux', \App\Http\Controllers\JournalController::class)->parameters(['journaux' => 'journal']);

    // Exports PDF
    Route::get('/stages/{stage}/attestation', [\App\Http\Controllers\ExportController::class, 'attestation'])->name('stages.attestation');
    Route::get('/stages/{stage}/report', [\App\Http\Controllers\ExportController::class, 'evaluationReport'])->name('stages.report');

    // Exports & Imports Excel
    Route::get('/export/stages', [\App\Http\Controllers\ExportController::class, 'stagesExcel'])->name('export.stages.excel');
    Route::post('/import/stages', [\App\Http\Controllers\ImportController::class, 'stages'])->name('import.stages');
    
    Route::get('/export/stagiaires', [\App\Http\Controllers\ExportController::class, 'stagiairesExcel'])->name('export.stagiaires.excel');
    Route::post('/import/stagiaires', [\App\Http\Controllers\ImportController::class, 'stagiaires'])->name('import.stagiaires');
    
    Route::get('/export/entreprises', [\App\Http\Controllers\ExportController::class, 'entreprisesExcel'])->name('export.entreprises.excel');
    Route::post('/import/entreprises', [\App\Http\Controllers\ImportController::class, 'entreprises'])->name('import.entreprises');
});


// Optional: Simple documentation redirect
Route::get('/api/documentation', function () {
    return redirect('https://documenter.getpostman.com/view/your-api-docs');
});
