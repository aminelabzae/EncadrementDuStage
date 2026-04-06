<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    
    public function run(): void
    {
        $roles = [
            [
                'code' => 'ADMIN',
                'libelle' => 'Administrateur',
                'description' => 'Accès complet au système',
            ],
            [
                'code' => 'STAGIAIRE',
                'libelle' => 'Stagiaire',
                'description' => 'Accès pour les stagiaires',
            ],
            [
                'code' => 'ENCADRANT',
                'libelle' => 'Encadrant',
                'description' => 'Accès pour les encadrants d\'entreprise',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['code' => $role['code']], $role);
        }
    }
}
