@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Vue d\'ensemble — ' . now()->locale('fr')->isoFormat('dddd D MMMM YYYY'))

@section('content')

{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Commandes en cours --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Aujourd'hui</span>
        </div>
        <p class="text-2xl font-black text-gray-900">{{ $stats['orders_active'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5 font-medium">Commandes en cours</p>
    </div>

    {{-- Commandes validées --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Aujourd'hui</span>
        </div>
        <p class="text-2xl font-black text-gray-900">{{ $stats['orders_paid_today'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5 font-medium">Commandes validées</p>
    </div>

    {{-- Recettes journalières --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Aujourd'hui</span>
        </div>
        <p class="text-xl font-black text-gray-900">{{ number_format($stats['revenue_today'], 0, ',', ' ') }}</p>
        <p class="text-xs text-gray-400 mt-0.5 font-medium">Recettes (FCFA)</p>
    </div>

    {{-- Ruptures --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Stock</span>
        </div>
        <p class="text-2xl font-black {{ $stats['out_of_stock'] > 0 ? 'text-red-500' : 'text-gray-300' }}">
            {{ $stats['out_of_stock'] }}
        </p>
        <p class="text-xs text-gray-400 mt-0.5 font-medium">Ruptures de stock</p>
    </div>

</div>

{{-- ===== GRAPHIQUES ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Commandes par mois --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-900 text-sm">Nombre de commandes par mois</h3>
                <p class="text-xs text-gray-400 mt-0.5">12 derniers mois</p>
            </div>
            <div class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
        <div style="position:relative; height:220px;">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>

    {{-- Produits par catégorie --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-900 text-sm">Produits par catégorie</h3>
                <p class="text-xs text-gray-400 mt-0.5">Répartition du catalogue actif</p>
            </div>
            <div class="w-8 h-8 bg-purple-50 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                </svg>
            </div>
        </div>
        <div style="position:relative; height:220px;">
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>

</div>

{{-- ===== DERNIÈRES COMMANDES ===== --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-gray-900 text-sm">Dernières commandes</h3>
            <p class="text-xs text-gray-400 mt-0.5">5 commandes les plus récentes</p>
        </div>
        <a href="{{ route('admin.orders.index') }}"
           class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
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
            <tbody class="divide-y divide-gray-50">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td">
                        <span class="font-mono text-xs font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">
                            {{ $order->reference }}
                        </span>
                    </td>
                    <td class="table-td">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center text-xs font-bold text-blue-600 flex-shrink-0">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-800 text-sm">{{ $order->user->name }}</span>
                        </div>
                    </td>
                    <td class="table-td font-bold text-gray-900">{{ $order->formatted_total }}</td>
                    <td class="table-td">
                        <span class="badge-{{ $order->status }}">{{ $order->status_label }}</span>
                    </td>
                    <td class="table-td text-gray-400 text-xs">
                        {{ $order->created_at->diffForHumans() }}
                    </td>
                    <td class="table-td text-right">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-blue-600 hover:text-blue-800 text-xs font-semibold">
                            Détails →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <p class="text-gray-300 text-sm">Aucune commande pour le moment</p>
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
    Chart.defaults.font.family = "'Figtree', sans-serif";
    Chart.defaults.font.size   = 11;
    Chart.defaults.color       = '#9ca3af';

    // ── Commandes par mois ──────────────────────────────
    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($ordersPerMonth->pluck('month')) !!},
            datasets: [{
                label: 'Commandes',
                data:  {!! json_encode($ordersPerMonth->pluck('total')) !!},
                backgroundColor: '#2563eb',
                hoverBackgroundColor: '#1d4ed8',
                borderRadius: 6,
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
                    callbacks: {
                        label: ctx => ' ' + ctx.parsed.y + ' commande(s)'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { precision: 0, stepSize: 1 }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // ── Produits par catégorie ──────────────────────────
    new Chart(document.getElementById('categoriesChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($productsByCategory->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($productsByCategory->pluck('total')) !!},
                backgroundColor: [
                    '#2563eb', '#0ea5e9', '#8b5cf6',
                    '#10b981', '#f59e0b', '#ef4444', '#f97316'
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 12,
                        usePointStyle: true,
                        pointStyleWidth: 8,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + ctx.label + ' : ' + ctx.parsed + ' produit(s)'
                    }
                }
            },
            cutout: '68%',
        }
    });
</script>
@endpush