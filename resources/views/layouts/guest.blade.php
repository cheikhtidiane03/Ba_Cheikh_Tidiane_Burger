<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            const t = localStorage.getItem('theme');
            const d = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (t === 'dark' || (!t && d)) document.documentElement.classList.add('dark');
        })();
    </script>
    <style>
        body { font-family: 'Figtree', sans-serif; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim-auth { animation: fadeUp 0.4s ease both; }
    </style>
</head>
<body class="h-full antialiased bg-slate-50 dark:bg-slate-950">

<div class="min-h-screen flex">

    {{-- Panneau gauche décoratif --}}
    <div class="hidden lg:flex lg:w-1/2 bg-blue-600 flex-col items-center justify-center p-12 relative overflow-hidden">
        {{-- Cercles décoratifs --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-500 rounded-full -translate-x-1/2 -translate-y-1/2 opacity-50"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-700 rounded-full translate-x-1/3 translate-y-1/3 opacity-40"></div>

        <div class="relative z-10 text-center">
            <div class="text-8xl mb-6">🍔</div>
            <h1 class="text-4xl font-black text-white mb-4 leading-tight">
                ISI BURGER
            </h1>
            <p class="text-blue-100 text-lg leading-relaxed max-w-sm">
                Gérez vos commandes, vos produits et vos paiements en toute simplicité.
            </p>

            <div class="mt-10 grid grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                    <p class="text-2xl font-black text-white">🍔</p>
                    <p class="text-xs text-blue-100 mt-1 font-medium">Produits</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                    <p class="text-2xl font-black text-white">📋</p>
                    <p class="text-xs text-blue-100 mt-1 font-medium">Commandes</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                    <p class="text-2xl font-black text-white">💰</p>
                    <p class="text-xs text-blue-100 mt-1 font-medium">Paiements</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Panneau droit : formulaire --}}
    <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md anim-auth">

            {{-- Logo mobile (visible seulement sur mobile) --}}
            <div class="lg:hidden flex items-center gap-3 mb-8 justify-center">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-lg">
                    🍔
                </div>
                <span class="text-2xl font-black text-gray-900 dark:text-white">ISI BURGER</span>
            </div>

            {{-- Carte formulaire --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 p-8">
                {{ $slot }}
            </div>

        </div>
    </div>

</div>

</body>
</html>