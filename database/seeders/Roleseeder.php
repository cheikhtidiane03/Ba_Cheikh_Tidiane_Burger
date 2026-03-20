<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Vider le cache Spatie avant de créer les rôles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // -----------------------------------------------
        // Création des permissions
        // -----------------------------------------------
        $permissions = [
            // Produits
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            // Commandes
            'orders.view-all',
            'orders.view-own',
            'orders.create',
            'orders.update-status',
            'orders.cancel',

            // Paiements
            'payments.create',
            'payments.view',

            // Statistiques
            'stats.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // -----------------------------------------------
        // Création des rôles et attribution des permissions
        // -----------------------------------------------

        // Gestionnaire : accès complet
        $gestionnaire = Role::firstOrCreate(['name' => 'gestionnaire']);
        $gestionnaire->syncPermissions($permissions);

        // Client : accès limité
        $client = Role::firstOrCreate(['name' => 'client']);
        $client->syncPermissions([
            'orders.view-own',
            'orders.create',
        ]);
    }
}