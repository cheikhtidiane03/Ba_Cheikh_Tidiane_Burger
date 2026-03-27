<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Afficher le formulaire d'inscription
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription — assigne automatiquement le rôle "client"
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Tout nouvel utilisateur inscrit est un client
        $user->assignRole('client');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('client.catalog.index')
                         ->with('success', 'Compte créé avec succès ! Bienvenue ' . $user->name . ' !');
    }
}