<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Stagiaire;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StagiaireSeeder extends Seeder
{
    
    public function run(): void
    {
        $stagiaireRole = Role::where('code', 'STAGIAIRE')->first();

        $stagiaires = [
            [
                'nom' => 'Alami',
                'prenom' => 'Ahmed',
                'email' => 'ahmed.alami@stagiaire.com',
                'ecole' => 'ENSAS',
                'filiere' => 'Génie Informatique',
                'niveau' => '2ème année',
            ],
            [
                'nom' => 'Bennani',
                'prenom' => 'Sara',
                'email' => 'sara.bennani@stagiaire.com',
                'ecole' => 'FST',
                'filiere' => 'Génie Logiciel',
                'niveau' => '3ème année',
            ],
        ];

        foreach ($stagiaires as $stagiaireData) {
            $user = User::updateOrCreate(
                ['email' => $stagiaireData['email']],
                [
                    'name' => $stagiaireData['prenom'] . ' ' . $stagiaireData['nom'],
                    'nom' => $stagiaireData['nom'],
                    'prenom' => $stagiaireData['prenom'],
                    'password' => Hash::make('password'),
                    'mot_de_passe' => Hash::make('password'),
                    'uuid' => (string) Str::uuid(),
                    'etat' => 'actif',
                ]
            );

            if ($stagiaireRole && !$user->roles()->where('role_id', $stagiaireRole->id)->exists()) {
                $user->roles()->attach($stagiaireRole->id);
            }

            Stagiaire::updateOrCreate(
                ['utilisateur_id' => $user->id],
                [
                    'ecole' => $stagiaireData['ecole'],
                    'filiere' => $stagiaireData['filiere'],
                    'niveau' => $stagiaireData['niveau'],
                    'telephone' => '0600000000',
                ]
            );
        }
    }
}
