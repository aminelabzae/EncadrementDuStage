<?php

namespace App\Exports;

use App\Models\Entreprise;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EntreprisesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Entreprise::with('adresse')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom',
            'Ville',
            'Pays',
            'Secteur',
        ];
    }

    public function map($entreprise): array
    {
        return [
            $entreprise->id,
            $entreprise->nom,
            $entreprise->adresse->ville ?? 'N/A',
            $entreprise->adresse->pays ?? 'N/A',
            $entreprise->secteur ?? 'N/A',
        ];
    }
}
