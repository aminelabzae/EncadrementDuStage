<?php

namespace App\Imports;

use App\Models\Stagiaire;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StagiairesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // On crée l'utilisateur d'abord
        $user = User::create([
            'name'     => $row['nom_complet'],
            'email'    => $row['email'],
            'password' => Hash::make('password123'), // Mot de passe par défaut
        ]);

        // On lui assigne le rôle STAGIAIRE
        $roleStagiaire = Role::where('name', 'STAGIAIRE')->first();
        if ($roleStagiaire) {
            $user->roles()->attach($roleStagiaire);
        }

        // On crée le profil stagiaire lié
        return new Stagiaire([
            'utilisateur_id' => $user->id,
            'filiere'        => $row['filiere'],
            'ecole'          => $row['ecole'],
            'niveau'         => $row['niveau'],
            'telephone'      => $row['telephone'] ?? null,
        ]);
    }
}
