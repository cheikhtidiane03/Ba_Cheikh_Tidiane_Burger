<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ISI BURGER') — Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Figtree', sans-serif; }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }

        .nav-active { position: relative; }
        .nav-active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 55%;
            background: #2563eb;
            border-radius: 0 3px 3px 0;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes toastIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse-online {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.85); }
        }

        .anim-up    { animation: slideUp 0.3s ease both; }
        .toast-anim { animation: toastIn 0.3s ease both; }

        *, *::before, *::after {
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        button, a, input, select, textarea, svg, path {
            transition-duration: 0.15s;
        }
    </style>
</head>

<body class="h-full antialiased bg-slate-50 dark:bg-slate-950 text-gray-900 dark:text-slate-100">

{{-- TOASTS --}}
<div x-data="{
        toasts: [],
        add(t) { const id=Date.now(); this.toasts.push({id,...t}); setTimeout(()=>this.rm(id),4500); },
        rm(id) { this.toasts=this.toasts.filter(t=>t.id!==id); }
     }"
     @toast.window="add($event.detail)"
     class="fixed top-4 right-4 z-[200] space-y-2 w-72 pointer-events-none">
    <template x-for="t in toasts" :key="t.id">
        <div class="toast-anim pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-2xl shadow-lg border text-sm font-medium"
             :class="{
                'bg-white border-emerald-200 text-emerald-700 dark:bg-slate-800 dark:border-emerald-700/50 dark:text-emerald-400': t.type==='success',
                'bg-white border-red-200 text-red-700 dark:bg-slate-800 dark:border-red-700/50 dark:text-red-400': t.type==='error',
                'bg-white border-amber-200 text-amber-700 dark:bg-slate-800 dark:border-amber-700/50 dark:text-amber-400': t.type==='warning',
             }">
            <span x-text="t.icon" class="text-lg flex-shrink-0"></span>
            <span x-text="t.msg"  class="flex-1 text-xs leading-snug"></span>
            <button @click="rm(t.id)" class="opacity-40 hover:opacity-100 transition text-base">✕</button>
        </div>
    </template>
</div>

{{-- Flash → Toast --}}
@if(session('success'))
<script>
document.addEventListener('alpine:init', () => setTimeout(() =>
    window.dispatchEvent(new CustomEvent('toast',
        { detail: { type:'success', msg:"{{ addslashes(session('success')) }}", icon:'✅' } })), 200));
</script>
@endif
@if(session('error'))
<script>
document.addEventListener('alpine:init', () => setTimeout(() =>
    window.dispatchEvent(new CustomEvent('toast',
        { detail: { type:'error', msg:"{{ addslashes(session('error')) }}", icon:'❌' } })), 200));
</script>
@endif
@if(session('delete'))
<script>
document.addEventListener('alpine:init', () => setTimeout(() =>
    window.dispatchEvent(new CustomEvent('toast',
        { detail: { type:'error', msg:"{{ addslashes(session('delete')) }}", icon:'🗑️' } })), 200));
</script>
@endif

{{-- LAYOUT --}}
<div x-data="{ open: true }" class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside :class="open ? 'w-56' : 'w-14'"
           class="flex-shrink-0 flex flex-col border-r overflow-hidden
                  bg-white border-gray-100 shadow-sm
                  dark:bg-slate-900 dark:border-slate-800
                  transition-[width] duration-250 ease-in-out">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-3.5 py-4 border-b border-gray-100 dark:border-slate-800 min-h-[57px]">
            <div class="w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center text-sm flex-shrink-0 shadow-md shadow-blue-600/30">
                🍔
            </div>
            <div x-show="open" x-cloak class="min-w-0 overflow-hidden">
                <p class="font-black text-sm text-gray-900 dark:text-white leading-tight">ISI BURGER</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"
                          style="animation: pulse-online 2s ease-in-out infinite"></span>
                    <p class="text-xs text-gray-400 dark:text-slate-500 font-medium">En ligne</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-2 space-y-0.5 overflow-y-auto">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                      {{ request()->routeIs('admin.dashboard') ? 'nav-active bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 hover:text-gray-900 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span x-show="open" x-cloak class="truncate">Dashboard</span>
            </a>

            {{-- Produits --}}
            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                      {{ request()->routeIs('admin.products.*') ? 'nav-active bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 hover:text-gray-900 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span x-show="open" x-cloak class="truncate">Produits</span>
            </a>

            {{-- Commandes --}}
            <a href="{{ route('admin.orders.index') }}"
               class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                      {{ request()->routeIs('admin.orders.*') ? 'nav-active bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 hover:text-gray-900 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span x-show="open" x-cloak class="truncate">Commandes</span>
            </a>

            {{-- Paiements --}}
            <a href="{{ route('admin.payments.index') }}"
               class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                      {{ request()->routeIs('admin.payments.*') ? 'nav-active bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 hover:text-gray-900 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span x-show="open" x-cloak class="truncate">Paiements</span>
            </a>

        </nav>

        {{-- Bas sidebar --}}
        <div class="border-t border-gray-100 dark:border-slate-800 p-2 space-y-1">

            {{-- Toggle thème --}}
            <button onclick="toggleTheme()"
                    class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-sm font-medium
                           text-gray-500 dark:text-slate-400
                           hover:bg-gray-50 dark:hover:bg-slate-800
                           hover:text-gray-900 dark:hover:text-white
                           transition-all duration-150">
                <span id="themeIcon" class="text-base flex-shrink-0">🌙</span>
                <span x-show="open" x-cloak id="themeLabel" class="text-xs truncate">Mode sombre</span>
            </button>

            {{-- User --}}
            <div class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl bg-gray-50 dark:bg-slate-800">
                <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700
                            flex items-center justify-center font-bold text-white text-xs flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div x-show="open" x-cloak class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500 truncate">Gestionnaire</p>
                </div>
                <form x-show="open" x-cloak method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Déconnexion"
                            class="p-1 rounded-lg text-gray-300 dark:text-slate-600
                                   hover:text-red-500 dark:hover:text-red-400
                                   hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </aside>

    {{-- CONTENU --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="flex-shrink-0 px-5 py-3 flex items-center justify-between border-b
                       bg-white border-gray-100 dark:bg-slate-900 dark:border-slate-800">
            <div class="flex items-center gap-3 min-w-0">
                <button @click="open = !open"
                        class="p-1.5 rounded-xl text-gray-400 dark:text-slate-500
                               hover:bg-gray-100 dark:hover:bg-slate-800
                               hover:text-gray-600 dark:hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="min-w-0">
                    <h1 class="text-sm font-bold text-gray-900 dark:text-white truncate">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-400 dark:text-slate-500 truncate">@yield('page-subtitle', '')</p>
                </div>
            </div>
            <span class="hidden sm:block text-xs text-gray-400 dark:text-slate-500
                         bg-gray-50 dark:bg-slate-800 px-3 py-1.5 rounded-xl
                         border border-gray-100 dark:border-slate-700">
                {{ now()->locale('fr')->isoFormat('ddd D MMM YYYY') }}
            </span>
        </header>

        {{-- Contenu --}}
        <main class="flex-1 overflow-y-auto p-5 bg-slate-50 dark:bg-slate-950 anim-up">
            @yield('content')
        </main>

    </div>
</div>

<script>
function toggleTheme() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    document.getElementById('themeIcon').textContent  = isDark ? '☀️' : '🌙';
    document.getElementById('themeLabel').textContent = isDark ? 'Mode clair' : 'Mode sombre';
}
document.addEventListener('DOMContentLoaded', () => {
    const isDark = document.documentElement.classList.contains('dark');
    const icon  = document.getElementById('themeIcon');
    const label = document.getElementById('themeLabel');
    if (icon)  icon.textContent  = isDark ? '☀️' : '🌙';
    if (label) label.textContent = isDark ? 'Mode clair' : 'Mode sombre';
});
</script>

@stack('scripts')
</body>
</html>