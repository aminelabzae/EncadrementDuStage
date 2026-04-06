<?php

namespace App\Imports;

use App\Models\Stage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StagesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Stage([
            'sujet'          => $row['sujet'],
            'type'           => $row['type'],
            'date_debut'     => $row['date_debut'],
            'date_fin'       => $row['date_fin'] ?? null,
            'statut'         => $row['statut'] ?? 'en_cours',
            'stagiaire_id'   => $row['stagiaire_id'],
            'entreprise_id'  => $row['entreprise_id'],
            'encadrant_id'   => $row['encadrant_id'] ?? null,
        ]);
    }
}
