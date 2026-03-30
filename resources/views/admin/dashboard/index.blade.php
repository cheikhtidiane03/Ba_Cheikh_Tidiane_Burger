@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Vue d\'ensemble — ' . now()->locale('fr')->isoFormat('dddd D MMMM YYYY'))

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5
                border-l-4 border-l-amber-400">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-bold text-amber-600 dark:text-amber-400
                         bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 rounded-full">
                Aujourd'hui
            </span>
        </div>
        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $stats['orders_active'] }}</p>
        <p class="text-xs text-gray-400 dark:text-slate-500 font-medium mt-0.5">Commandes en cours</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5
                border-l-4 border-l-emerald-400">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400
                         bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-full">
                Validées
            </span>
        </div>
        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $stats['orders_paid_today'] }}</p>
        <p class="text-xs text-gray-400 dark:text-slate-500 font-medium mt-0.5">Commandes payées aujourd'hui</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5
                border-l-4 border-l-blue-500">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"/>
                </svg>
            </div>
            <span class="text-xs font-bold text-blue-600 dark:text-blue-400
                         bg-blue-50 dark:bg-blue-500/10 px-2 py-0.5 rounded-full">
                Recettes
            </span>
        </div>
        <p class="text-2xl font-black text-gray-900 dark:text-white leading-tight">
            {{ number_format($stats['revenue_today'], 0, ',', ' ') }}
            <span class="text-sm font-medium text-gray-400 dark:text-slate-500">FCFA</span>
        </p>
        <p class="text-xs text-gray-400 dark:text-slate-500 font-medium mt-0.5">Recettes journalières</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5
                border-l-4 {{ $stats['out_of_stock'] > 0 ? 'border-l-red-400' : 'border-l-gray-200 dark:border-l-slate-700' }}">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 {{ $stats['out_of_stock'] > 0 ? 'bg-red-50 dark:bg-red-500/10' : 'bg-gray-50 dark:bg-slate-700' }} rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 {{ $stats['out_of_stock'] > 0 ? 'text-red-500' : 'text-gray-300 dark:text-slate-600' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="text-xs font-bold {{ $stats['out_of_stock'] > 0 ? 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10' : 'text-gray-400 dark:text-slate-500 bg-gray-50 dark:bg-slate-700' }} px-2 py-0.5 rounded-full">
                Stock
            </span>
        </div>
        <p class="text-3xl font-black {{ $stats['out_of_stock'] > 0 ? 'text-red-500' : 'text-gray-300 dark:text-slate-600' }}">
            {{ $stats['out_of_stock'] }}
        </p>
        <p class="text-xs text-gray-400 dark:text-slate-500 font-medium mt-0.5">Ruptures de stock</p>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">
                    Nombre de commandes par mois
                </h3>
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">12 derniers mois</p>
            </div>
            <div class="w-9 h-9 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
        <div style="position:relative; height:220px;">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">
                    Nombre de produits par catégorie
                </h3>
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">Catalogue actif (non archivés)</p>
            </div>
            <div class="w-9 h-9 bg-purple-50 dark:bg-purple-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
            </div>
        </div>
        <div style="position:relative; height:220px;">
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>

</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">

    <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-gray-900 dark:text-white text-sm">Dernières commandes</h3>
            <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">6 commandes les plus récentes</p>
        </div>
        <a href="{{ route('admin.orders.index') }}"
           class="text-xs font-semibold text-blue-600 dark:text-blue-400
                  hover:text-blue-800 dark:hover:text-blue-300
                  flex items-center gap-1 transition">
            Voir toutes
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-th">Référence</th>
                    <th class="table-th">Client</th>
                    <th class="table-th">Montant</th>
                    <th class="table-th">Statut</th>
                    <th class="table-th">Heure</th>
                    <th class="table-th text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="table-td">
                        <span class="font-mono text-xs font-bold text-gray-600 dark:text-slate-400
                                     bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded-lg">
                            {{ $order->reference }}
                        </span>
                    </td>
                    <td class="table-td">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-blue-100 dark:bg-blue-500/20 rounded-lg
                                        flex items-center justify-center text-xs font-bold
                                        text-blue-600 dark:text-blue-400 flex-shrink-0">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-800 dark:text-white text-sm">
                                {{ $order->user->name }}
                            </span>
                        </div>
                    </td>
                    <td class="table-td font-bold text-gray-900 dark:text-white">
                        {{ $order->formatted_total }}
                    </td>
                    <td class="table-td">
                        <span class="badge-{{ $order->status }}">{{ $order->status_label }}</span>
                    </td>
                    <td class="table-td text-gray-400 dark:text-slate-500 text-xs">
                        {{ $order->created_at->diffForHumans() }}
                    </td>
                    <td class="table-td text-right">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300
                                  text-xs font-semibold transition">
                            Détails →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <p class="text-4xl mb-2">📋</p>
                        <p class="text-sm text-gray-300 dark:text-slate-600">
                            Aucune commande pour le moment
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const isDarkMode = document.documentElement.classList.contains('dark');
Chart.defaults.font.family = "'Figtree', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = isDarkMode ? '#94a3b8' : '#9ca3af';

const ordersLabels = {!! json_encode($ordersPerMonth->pluck('month')) !!};
const ordersTotals = {!! json_encode($ordersPerMonth->pluck('total')->map(fn($v) => (int)$v)) !!};

const catLabels = {!! json_encode($productsByCategory->pluck('name')) !!};
const catTotals = {!! json_encode($productsByCategory->pluck('total')->map(fn($v) => (int)$v)) !!};

const ordersChart = new Chart(document.getElementById('ordersChart'), {
    type: 'bar',
    data: {
        labels: ordersLabels,
        datasets: [{
            label: 'Commandes',
            data: ordersTotals,
            backgroundColor: isDarkMode ? 'rgba(59,130,246,0.7)' : '#2563eb',
            hoverBackgroundColor: isDarkMode ? 'rgba(59,130,246,1)' : '#1d4ed8',
            borderRadius: 8,
            borderSkipped: false,
            barThickness: 28,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: isDarkMode ? '#1e293b' : '#fff',
                titleColor: isDarkMode ? '#e2e8f0' : '#1e293b',
                bodyColor: isDarkMode ? '#94a3b8' : '#64748b',
                borderColor: isDarkMode ? '#334155' : '#e2e8f0',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: ctx => '  ' + ctx.parsed.y + ' commande(s)'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: isDarkMode ? 'rgba(51,65,85,0.5)' : '#f1f5f9' },
                ticks: { precision: 0, stepSize: 1 }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

const palette = isDarkMode
    ? ['rgba(59,130,246,.8)','rgba(14,165,233,.8)','rgba(139,92,246,.8)',
       'rgba(16,185,129,.8)','rgba(245,158,11,.8)','rgba(239,68,68,.8)','rgba(249,115,22,.8)']
    : ['#2563eb','#0ea5e9','#8b5cf6','#10b981','#f59e0b','#ef4444','#f97316'];

const catChart = new Chart(document.getElementById('categoriesChart'), {
    type: 'doughnut',
    data: {
        labels: catLabels,
        datasets: [{
            data: catTotals,
            backgroundColor: palette,
            borderWidth: 3,
            borderColor: isDarkMode ? '#1e293b' : '#ffffff',
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    padding: 14,
                    usePointStyle: true,
                    pointStyleWidth: 8,
                    font: { size: 11 },
                    color: isDarkMode ? '#94a3b8' : '#6b7280',
                }
            },
            tooltip: {
                backgroundColor: isDarkMode ? '#1e293b' : '#fff',
                titleColor: isDarkMode ? '#e2e8f0' : '#1e293b',
                bodyColor: isDarkMode ? '#94a3b8' : '#64748b',
                borderColor: isDarkMode ? '#334155' : '#e2e8f0',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: ctx => '  ' + ctx.label + ' : ' + ctx.parsed + ' produit(s)'
                }
            }
        },
        cutout: '68%',
    }
});

document.addEventListener('themeChanged', () => {
    location.reload(); 
});
</script>
@endpush