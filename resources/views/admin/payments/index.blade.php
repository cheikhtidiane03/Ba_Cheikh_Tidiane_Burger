@extends('layouts.admin')

@section('title', 'Paiements')
@section('page-title', 'Paiements')
@section('page-subtitle', 'Historique des paiements enregistrés')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
    <div class="stat-card border-l-4 border-emerald-500">
        <div class="stat-icon bg-emerald-50">
            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Recettes aujourd'hui</p>
            <p class="text-2xl font-black text-gray-900 mt-1">
                {{ number_format($totalToday, 0, ',', ' ') }}
                <span class="text-sm font-medium text-gray-400">FCFA</span>
            </p>
        </div>
    </div>

    <div class="stat-card border-l-4 border-blue-500">
        <div class="stat-icon bg-blue-50">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Recettes ce mois</p>
            <p class="text-2xl font-black text-gray-900 mt-1">
                {{ number_format($totalMonth, 0, ',', ' ') }}
                <span class="text-sm font-medium text-gray-400">FCFA</span>
            </p>
        </div>
    </div>
</div>

{{-- Tableau --}}
<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-th">Commande</th>
                    <th class="table-th">Client</th>
                    <th class="table-th">Montant</th>
                    <th class="table-th">Mode</th>
                    <th class="table-th">Enregistré par</th>
                    <th class="table-th">Date paiement</th>
                    <th class="table-th text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td">
                        <span class="font-mono text-xs font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">
                            {{ $payment->order->reference }}
                        </span>
                    </td>
                    <td class="table-td font-medium text-gray-800">{{ $payment->order->user->name }}</td>
                    <td class="table-td font-black text-emerald-600 text-base">{{ $payment->formatted_amount }}</td>
                    <td class="table-td">
                        <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                            💵 Espèces
                        </span>
                    </td>
                    <td class="table-td text-gray-500 text-sm">{{ $payment->recorder->name }}</td>
                    <td class="table-td text-gray-500 text-sm">
                        {{ $payment->paid_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="table-td text-right">
                        <a href="{{ route('admin.orders.show', $payment->order) }}"
                           class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                            Voir →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-14">
                        <div class="text-4xl mb-2">💳</div>
                        <p class="text-gray-400">Aucun paiement enregistré</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    @endif
</div>

@endsection