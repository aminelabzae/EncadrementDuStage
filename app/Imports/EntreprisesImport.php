<?php

namespace App\Imports;

use App\Models\Entreprise;
use App\Models\Adresse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EntreprisesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // On crée l'adresse d'abord
        $adresse = Adresse::create([
            'rue'   => $row['rue'] ?? 'N/A',
            'ville' => $row['ville'],
            'pays'  => $row['pays'] ?? 'Maroc',
        ]);

        // On crée l'entreprise liée
        return new Entreprise([
            'nom'        => $row['nom'],
            'adresse_id' => $adresse->id,
            'secteur'    => $row['secteur'] ?? null,
            'contact'    => $row['contact'] ?? null,
        ]);
    }
}
