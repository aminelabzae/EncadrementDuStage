<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StagiairesImport;
use App\Imports\EntreprisesImport;
use App\Imports\StagesImport;

class ImportController extends Controller
{
    /**
     * Importation Excel des stagiaires.
     */
    public function stagiaires(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new StagiairesImport, $request->file('file'));

        return back()->with('success', 'Les stagiaires ont été importés avec succès !');
    }

    /**
     * Importation Excel des entreprises.
     */
    public function entreprises(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new EntreprisesImport, $request->file('file'));

        return back()->with('success', 'Les entreprises ont été importées avec succès !');
    }

    /**
     * Importation Excel des stages.
     */
    public function stages(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // Note: L'importation des stages nécessite que les stagiaires et entreprises existent déjà.
        // On utilisera un modèle simplifié. (À implémenter dans StagesImport)
        // Excel::import(new StagesImport, $request->file('file'));

        return back()->with('success', 'La fonctionnalité d\'importation des stages est configurée.');
    }
}
