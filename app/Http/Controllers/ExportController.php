<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StagesExport;
use App\Exports\StagiairesExport;
use App\Exports\EntreprisesExport;

class ExportController extends Controller
{
    /**
     * Exportation Excel de la liste des stages.
     */
    public function stagesExcel()
    {
        return Excel::download(new StagesExport, 'Liste_Stages_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Exportation Excel de la liste des stagiaires.
     */
    public function stagiairesExcel()
    {
        return Excel::download(new StagiairesExport, 'Liste_Stagiaires_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Exportation Excel de la liste des entreprises.
     */
    public function entreprisesExcel()
    {
        return Excel::download(new EntreprisesExport, 'Liste_Entreprises_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Génère l'attestation de stage en PDF.
     */
    public function attestation(Stage $stage)
    {
        // On vérifie que le stage est bien terminé pour délivrer l'attestation
        // (Optionnel : vous pouvez retirer cette condition si besoin)
        /*
        if ($stage->statut !== 'termine') {
            return back()->with('error', 'L\'attestation ne peut être générée que pour un stage terminé.');
        }
        */

        $data = [
            'stage' => $stage->load(['stagiaire.utilisateur', 'entreprise', 'encadrant.utilisateur']),
            'date' => now()->format('d/m/Y'),
        ];

        $pdf = Pdf::loadView('pdf.attestation', $data);
        
        return $pdf->download('Attestation_Stage_' . $stage->stagiaire->utilisateur->name . '.pdf');
    }

    /**
     * Génère le rapport d'évaluation détaillé en PDF.
     */
    public function evaluationReport(Stage $stage)
    {
        $data = [
            'stage' => $stage->load(['stagiaire.utilisateur', 'entreprise', 'encadrant.utilisateur', 'evaluations']),
            'date' => now()->format('d/m/Y'),
        ];

        $pdf = Pdf::loadView('pdf.evaluation_report', $data);
        
        return $pdf->download('Rapport_Evaluation_' . $stage->stagiaire->utilisateur->name . '.pdf');
    }
}
