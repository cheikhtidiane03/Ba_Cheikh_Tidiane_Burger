@extends('layouts.admin')

@section('title', 'Commandes')
@section('page-title', 'Gestion des Commandes')
@section('page-subtitle', 'Suivez et gérez toutes les commandes')

@section('content')

{{-- Onglets statuts --}}
<div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
    @php
        $tabs = [
            ''          => ['label' => 'Toutes',        'count' => $counts['all']],
            'pending'   => ['label' => 'En attente',    'count' => $counts['pending']],
            'preparing' => ['label' => 'En préparation','count' => $counts['preparing']],
            'ready'     => ['label' => 'Prêtes',        'count' => $counts['ready']],
            'paid'      => ['label' => 'Payées',        'count' => $counts['paid']],
            'cancelled' => ['label' => 'Annulées',      'count' => $counts['cancelled']],
        ];
    @endphp

    @foreach($tabs as $value => $tab)
        <a href="{{ route('admin.orders.index', array_merge(request()->except('status', 'page'), $value ? ['status' => $value] : [])) }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-all
                  {{ request('status', '') === $value
                      ? 'bg-blue-600 text-white shadow-sm'
                      : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            {{ $tab['label'] }}
            <span class="text-xs px-1.5 py-0.5 rounded-full
                         {{ request('status', '') === $value ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-500' }}">
                {{ $tab['count'] }}
            </span>
        </a>
    @endforeach
</div>

{{-- Filtres --}}
<form method="GET" action="{{ route('admin.orders.index') }}"
      class="flex flex-wrap gap-3 mb-5 p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
    @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="🔍 Référence ou client..."
           class="form-input max-w-xs">
    <input type="date" name="date" value="{{ request('date') }}"
           class="form-input max-w-xs">
    <button type="submit" class="btn-primary">Filtrer</button>
    <a href="{{ route('admin.orders.index') }}" class="btn-secondary">Réinitialiser</a>
</form>

{{-- Tableau --}}
<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-th">Référence</th>
                    <th class="table-th">Client</th>
                    <th class="table-th">Articles</th>
                    <th class="table-th">Total</th>
                    <th class="table-th">Statut</th>
                    <th class="table-th">Date</th>
                    <th class="table-th text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td">
                        <span class="font-mono text-xs font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">
                            {{ $order->reference }}
                        </span>
                    </td>
                    <td class="table-td">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center text-xs font-bold text-blue-600">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $order->user->name }}</p>
                                <p class="text-gray-400 text-xs">{{ $order->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="table-td text-gray-500">
                        {{ $order->items->count() }} article(s)
                    </td>
                    <td class="table-td font-bold text-gray-900">{{ $order->formatted_total }}</td>
                    <td class="table-td">
                        <span class="badge-{{ $order->status }}">{{ $order->status_label }}</span>
                    </td>
                    <td class="table-td text-gray-400 text-xs">
                        <p>{{ $order->created_at->format('d/m/Y') }}</p>
                        <p>{{ $order->created_at->format('H:i') }}</p>
                    </td>
                    <td class="table-td text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn-primary text-xs py-1.5 px-3">
                                Détails
                            </a>
                            @if(!$order->isCancelled() && !$order->isPaid())
                                <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                                      onsubmit="return confirm('Annuler la commande {{ $order->reference }} ?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                        Annuler
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-14">
                        <div class="text-4xl mb-2">📋</div>
                        <p class="text-gray-400">Aucune commande trouvée</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@endsection