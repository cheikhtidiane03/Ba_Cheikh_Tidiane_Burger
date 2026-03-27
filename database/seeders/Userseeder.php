<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------------------------
        // Compte Gestionnaire
        // -----------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@isiburger.com'],
            [
                'name'     => 'Admin ISI BURGER',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('gestionnaire');

        // -----------------------------------------------
        // Compte Client de démonstration
        // -----------------------------------------------
        $client = User::firstOrCreate(
            ['email' => 'client@isiburger.com'],
            [
                'name'     => 'Client Test',
                'password' => Hash::make('password'),
            ]
        );
        $client->assignRole('client');

        $this->command->info('✅ Utilisateurs créés :');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['gestionnaire', 'admin@isiburger.com',  'password'],
                ['client',       'client@isiburger.com', 'password'],
            ]
        );
    }
}