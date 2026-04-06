<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\StagiaireController;
use App\Http\Controllers\EncadrantController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PieceJointeController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\JournalActiviteController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\RolePermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    
    // Password reset routes (if implemented)
    // Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    // Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
});

// Protected routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Stages
    Route::apiResource('stages', StageController::class);
    Route::prefix('stages/{stage}')->group(function () {
        // Journal entries
        Route::post('/journal', [StageController::class, 'addJournalEntry']);
        Route::get('/journal', [StageController::class, 'journal']);
        
        // Objectives
        Route::post('/objectifs', [StageController::class, 'addObjective']);
        Route::put('/objectifs/{objectif}', [StageController::class, 'updateObjective']);
        Route::delete('/objectifs/{objectif}', [StageController::class, 'deleteObjective']);
        
        // Evaluations
        Route::post('/evaluations', [StageController::class, 'addEvaluation']);
        
        // Visits
        Route::post('/visites', [StageController::class, 'addVisite']);
        
        // Presence
        Route::post('/presences', [StageController::class, 'markPresence']);
        Route::get('/presences', [StageController::class, 'presences']);
        
        // Incidents
        Route::post('/incidents', [StageController::class, 'addIncident']);
        
        // Documents
        Route::get('/documents', [StageController::class, 'documents']);
        Route::post('/documents', [StageController::class, 'uploadDocument']);
        Route::delete('/documents/{document}', [StageController::class, 'deleteDocument']);
        
        // Attestations
        Route::post('/attestations', [StageController::class, 'generateAttestation']);
        
        // Comments (polymorphic)
        Route::apiResource('commentaires', CommentaireController::class);
    });

    // Statistics
    Route::get('/statistiques/stages', [StageController::class, 'statistics']);

    // Entreprises
    Route::apiResource('entreprises', EntrepriseController::class);
    Route::get('/entreprises/{entreprise}/stages', [EntrepriseController::class, 'stages']);
    Route::get('/entreprises/{entreprise}/encadrants', [EntrepriseController::class, 'encadrants']);

    // Stagiaires
    Route::apiResource('stagiaires', StagiaireController::class);
    Route::get('/stagiaires/{stagiaire}/stages', [StagiaireController::class, 'stages']);
    Route::get('/stagiaires/{stagiaire}/stage-actuel', [StagiaireController::class, 'currentStage']);

    // Encadrants
    Route::apiResource('encadrants', EncadrantController::class);
    Route::get('/encadrants/{encadrant}/stages', [EncadrantController::class, 'stages']);

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::put('/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
    });

    // Files
    Route::prefix('files')->group(function () {
        Route::post('/upload', [PieceJointeController::class, 'upload']);
        Route::get('/{pieceJointe}', [PieceJointeController::class, 'download']);
        Route::delete('/{pieceJointe}', [PieceJointeController::class, 'destroy']);
    });

    // Comments (global)
    Route::apiResource('commentaires', CommentaireController::class)->except(['index', 'show']);

    // Activity Log (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/journal-activites', [JournalActiviteController::class, 'index']);
        Route::get('/journal-activites/{activite}', [JournalActiviteController::class, 'show']);
        
        // Parameters
        Route::apiResource('parametres', ParametreController::class);
        
        // Roles & Permissions
        Route::get('/roles', [RolePermissionController::class, 'roles']);
        Route::post('/roles', [RolePermissionController::class, 'createRole']);
        Route::put('/roles/{role}', [RolePermissionController::class, 'updateRole']);
        Route::delete('/roles/{role}', [RolePermissionController::class, 'deleteRole']);
        
        Route::get('/permissions', [RolePermissionController::class, 'permissions']);
        Route::post('/roles/{role}/permissions', [RolePermissionController::class, 'assignPermissions']);
        Route::delete('/roles/{role}/permissions/{permission}', [RolePermissionController::class, 'revokePermission']);
        
        // User management
        Route::get('/users', [UserController::class, 'index']);
        Route::put('/users/{user}/roles', [UserController::class, 'assignRoles']);
        Route::put('/users/{user}/status', [UserController::class, 'updateStatus']);
    });
});

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});