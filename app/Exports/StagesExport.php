<?php

namespace App\Exports;

use App\Models\Stage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StagesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Stage::with(['stagiaire.utilisateur', 'entreprise', 'encadrant.utilisateur'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Sujet',
            'Stagiaire',
            'Entreprise',
            'Encadrant',
            'Type',
            'Date Début',
            'Date Fin',
            'Statut',
        ];
    }

    public function map($stage): array
    {
        return [
            $stage->id,
            $stage->sujet,
            $stage->stagiaire->utilisateur->name ?? 'N/A',
            $stage->entreprise->nom ?? 'N/A',
            $stage->encadrant->utilisateur->name ?? 'N/A',
            $stage->type,
            $stage->date_debut ? $stage->date_debut->format('d/m/Y') : '',
            $stage->date_fin ? $stage->date_fin->format('d/m/Y') : '',
            $stage->statut,
        ];
    }
}
