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
            ['email' => 'fatou@gmail.com'],
            [
                'name'     => 'Fatou Ndiaye',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('gestionnaire');

        // -----------------------------------------------
        // Compte Client de démonstration
        // -----------------------------------------------
        $client = User::firstOrCreate(
            ['email' => 'cheikhtidianeb764@gmail.com'],
            [
                'name'     => 'Cheikh Tidiane Ba',
                'password' => Hash::make('password'),
            ]
        );
        $client->assignRole('client');

        //$this->command->info('✅ Utilisateurs créés :');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['gestionnaire', 'fatou@gmail.com',  'password'],
                ['client',       'cheikhtidianeb764@gmail.com', 'password'],
            ]
        );
    }
}