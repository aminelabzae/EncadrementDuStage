<?php

namespace App\Exports;

use App\Models\Stagiaire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StagiairesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Stagiaire::with('utilisateur')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom Complet',
            'Email',
            'Filière',
            'École',
            'Niveau',
        ];
    }

    public function map($stagiaire): array
    {
        return [
            $stagiaire->id,
            $stagiaire->utilisateur->name ?? 'N/A',
            $stagiaire->utilisateur->email ?? 'N/A',
            $stagiaire->filiere,
            $stagiaire->ecole,
            $stagiaire->niveau,
        ];
    }
}
