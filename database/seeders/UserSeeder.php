<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'aminelabzae@gmail.com'],
            [
                'name' => 'Amine Labzae',
                'nom' => 'Labzae',
                'prenom' => 'Amine',
                'password' => Hash::make('password'),
                'mot_de_passe' => Hash::make('password'),
                'uuid' => (string) Str::uuid(),
                'etat' => 'actif',
            ]
        );

        $adminRole = Role::where('code', 'ADMIN')->first();
        if ($adminRole && !$admin->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole->id);
        }
    }
}
