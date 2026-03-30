<x-guest-layout>

    {{-- Titre --}}
    <div class="mb-7">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white">Connexion</h2>
        <p class="text-sm text-gray-400 dark:text-slate-500 mt-1">
            Bienvenue ! Connectez-vous à votre compte.
        </p>
    </div>

    {{-- Status (mot de passe réinitialisé etc.) --}}
    @if (session('status'))
        <div class="flex items-center gap-3 mb-5 px-4 py-3 rounded-xl
                    bg-emerald-50 border border-emerald-200 text-emerald-700
                    dark:bg-emerald-500/10 dark:border-emerald-500/30 dark:text-emerald-400 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

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
                       required autofocus autocomplete="username">
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
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="form-label mb-0">Mot de passe</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold transition">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <input id="password" type="password" name="password"
                       placeholder="••••••••"
                       class="{{ $errors->has('password') ? 'form-input-error' : 'form-input' }} pl-10"
                       required autocomplete="current-password">
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

        {{-- Se souvenir de moi --}}
        <div class="flex items-center gap-2.5">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 rounded text-blue-600 border-gray-300 dark:border-slate-600
                          dark:bg-slate-700 focus:ring-blue-500">
            <label for="remember_me" class="text-sm text-gray-600 dark:text-slate-400">
                Se souvenir de moi
            </label>
        </div>

        {{-- Bouton connexion --}}
        <button type="submit" class="btn-primary w-full justify-center py-3 text-base mt-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Se connecter
        </button>

        {{-- Lien inscription --}}
        <p class="text-center text-sm text-gray-500 dark:text-slate-400 mt-4">
            Pas encore de compte ?
            <a href="{{ route('register') }}"
               class="text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-800 dark:hover:text-blue-300 transition">
                Créer un compte
            </a>
        </p>
    </form>

</x-guest-layout>