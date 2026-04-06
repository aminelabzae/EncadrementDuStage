<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use Illuminate\Database\Seeder;

class EntrepriseSeeder extends Seeder
{
    
    public function run(): void
    {
        $entreprises = [
            ['nom' => 'OCP Group'],
            ['nom' => 'Maroc Telecom'],
            ['nom' => 'Capgemini'],
            ['nom' => 'DXC Technology'],
        ];

        foreach ($entreprises as $entreprise) {
            Entreprise::updateOrCreate(['nom' => $entreprise['nom']], $entreprise);
        }
    }
}
