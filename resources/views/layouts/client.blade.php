<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ISI BURGER')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

    {{-- ===== NAVBAR ===== --}}
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('client.catalog.index') }}" class="flex items-center gap-2">
                <span class="text-3xl">🍔</span>
                <span class="font-bold text-xl text-orange-600">ISI BURGER</span>
            </a>

            {{-- Navigation centrale --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('client.catalog.index') }}"
                   class="{{ request()->routeIs('client.catalog.*') ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-600 hover:text-orange-600' }} pb-1 transition">
                    Notre Carte
                </a>
                <a href="{{ route('client.orders.index') }}"
                   class="{{ request()->routeIs('client.orders.*') ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-600 hover:text-orange-600' }} pb-1 transition">
                    Mes Commandes
                </a>
            </div>

            {{-- Partie droite : utilisateur --}}
            <div class="flex items-center gap-4">

                @auth
                    {{-- Panier (badge) --}}
                    <a href="{{ route('client.orders.index') }}" class="relative">
                        <svg class="w-6 h-6 text-gray-600 hover:text-orange-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </a>

                    {{-- Menu utilisateur --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 text-sm text-gray-700 hover:text-orange-600 transition">
                            <div class="w-8 h-8 rounded-full bg-orange-100 border border-orange-300 flex items-center justify-center font-bold text-orange-700 text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:block">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                            <a href="{{ route('client.orders.index') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">
                                Mes commandes
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-600 hover:text-orange-600 font-medium transition">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-sm bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 font-medium transition">
                        S'inscrire
                    </a>
                @endauth

            </div>
        </div>
    </nav>

    {{-- ===== ALERTES FLASH ===== --}}
    <div class="max-w-7xl mx-auto px-6 pt-4">
        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- ===== CONTENU ===== --}}
    <main class="max-w-7xl mx-auto px-6 py-6">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-gray-800 text-gray-400 text-center py-5 mt-12 text-sm">
        <p>🍔 <span class="text-white font-medium">ISI BURGER</span> — Tous droits réservés {{ date('Y') }}</p>
    </footer>

    @stack('scripts')

    {{-- Alpine.js pour les menus déroulants --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>