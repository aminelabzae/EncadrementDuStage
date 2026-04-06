<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Encadrant;
use App\Models\Entreprise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EncadrantSeeder extends Seeder
{
   
    public function run(): void
    {
        $encadrantRole = Role::where('code', 'ENCADRANT')->first();
        $entreprise = Entreprise::first();

        if (!$entreprise) {
            return;
        }

        $mentors = [
            [
                'nom' => 'Tazi',
                'prenom' => 'Karim',
                'email' => 'karim.tazi@entreprise.com',
                'poste' => 'Chef de projet',
            ],
            [
                'nom' => 'Idrissi',
                'prenom' => 'Laila',
                'email' => 'laila.idrissi@entreprise.com',
                'poste' => 'Développeur Senior',
            ],
        ];

        foreach ($mentors as $mentorData) {
            $user = User::updateOrCreate(
                ['email' => $mentorData['email']],
                [
                    'name' => $mentorData['prenom'] . ' ' . $mentorData['nom'],
                    'nom' => $mentorData['nom'],
                    'prenom' => $mentorData['prenom'],
                    'password' => Hash::make('password'),
                    'mot_de_passe' => Hash::make('password'),
                    'uuid' => (string) Str::uuid(),
                    'etat' => 'actif',
                ]
            );

            if ($encadrantRole && !$user->roles()->where('role_id', $encadrantRole->id)->exists()) {
                $user->roles()->attach($encadrantRole->id);
            }

            Encadrant::updateOrCreate(
                ['utilisateur_id' => $user->id],
                [
                    'entreprise_id' => $entreprise->id,
                    'poste' => $mentorData['poste'],
                    'telephone' => '0600000000',
                ]
            );
        }
    }
}
