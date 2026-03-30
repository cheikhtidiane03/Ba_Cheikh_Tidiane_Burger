<x-guest-layout>

    <div class="mb-7">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white">Créer un compte</h2>
        <p class="text-sm text-gray-400 dark:text-slate-500 mt-1">
            Rejoignez ISI BURGER pour commander vos burgers.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Nom --}}
        <div>
            <label for="name" class="form-label">Nom complet</label>
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <input id="name" type="text" name="name"
                       value="{{ old('name') }}"
                       placeholder="Votre nom"
                       class="{{ $errors->has('name') ? 'form-input-error' : 'form-input' }} pl-10"
                       required autofocus autocomplete="name">
            </div>
            @error('name')
                <p class="flex items-center gap-1.5 mt-1.5 text-xs font-semibold text-red-600 dark:text-red-400">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="form-label">Adresse email</label>
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <input id="email" type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="votre@email.com"
                       class="{{ $errors->has('email') ? 'form-input-error' : 'form-input' }} pl-10"
                       required autocomplete="username">
            </div>
            @error('email')
                <p class="flex items-center gap-1.5 mt-1.5 text-xs font-semibold text-red-600 dark:text-red-400">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Mot de passe --}}
        <div>
            <label for="password" class="form-label">Mot de passe</label>
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <input id="password" type="password" name="password"
                       placeholder="••••••••"
                       class="{{ $errors->has('password') ? 'form-input-error' : 'form-input' }} pl-10"
                       required autocomplete="new-password">
            </div>
            @error('password')
                <p class="flex items-center gap-1.5 mt-1.5 text-xs font-semibold text-red-600 dark:text-red-400">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Confirmer mot de passe --}}
        <div>
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       placeholder="••••••••"
                       class="{{ $errors->has('password_confirmation') ? 'form-input-error' : 'form-input' }} pl-10"
                       required autocomplete="new-password">
            </div>
            @error('password_confirmation')
                <p class="flex items-center gap-1.5 mt-1.5 text-xs font-semibold text-red-600 dark:text-red-400">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Bouton --}}
        <button type="submit" class="btn-primary w-full justify-center py-3 text-base mt-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Créer mon compte
        </button>

        {{-- Lien connexion --}}
        <p class="text-center text-sm text-gray-500 dark:text-slate-400 mt-4">
            Déjà un compte ?
            <a href="{{ route('login') }}"
               class="text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-800 dark:hover:text-blue-300 transition">
                Se connecter
            </a>
        </p>
    </form>

</x-guest-layout>