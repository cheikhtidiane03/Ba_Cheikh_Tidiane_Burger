@extends('layouts.client')

@section('title', 'Mes Commandes')
@section('page-title', 'Mes Commandes')
@section('page-subtitle', 'Historique de vos commandes')

@section('content')

@if($orders->isEmpty())
    <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-2xl
                border border-gray-100 dark:border-slate-700 shadow-sm">
        <p class="text-5xl mb-3">📋</p>
        <p class="font-bold text-gray-400 dark:text-slate-500 mb-1">Aucune commande</p>
        <p class="text-sm text-gray-300 dark:text-slate-600 mb-5">Passez votre première commande !</p>
        <a href="{{ route('client.catalog.index') }}" class="btn-primary">🍔 Voir le catalogue</a>
    </div>
@else
    <div class="space-y-3">
        @foreach($orders as $order)
        <a href="{{ route('client.orders.show', $order) }}"
           class="block bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700
                  rounded-2xl shadow-sm p-5
                  hover:shadow-md hover:border-blue-200 dark:hover:border-blue-700/50
                  transition-all duration-200 group">

            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-xl
                                flex items-center justify-center flex-shrink-0 text-lg
                                group-hover:bg-blue-100 dark:group-hover:bg-blue-500/20 transition">
                        🍔
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-0.5">
                            <span class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400
                                         bg-blue-50 dark:bg-blue-500/10 px-2 py-0.5 rounded-lg">
                                {{ $order->reference }}
                            </span>
                            <span class="badge-{{ $order->status }}">{{ $order->status_label }}</span>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-slate-500">
                            {{ $order->items->count() }} article(s) —
                            {{ $order->created_at->locale('fr')->isoFormat('D MMM YYYY à HH:mm') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <p class="font-black text-gray-900 dark:text-white">{{ $order->formatted_total }}</p>
                    <svg class="w-4 h-4 text-gray-300 dark:text-slate-600 group-hover:text-blue-400 transition"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>

            {{-- Barre progression --}}
            @if(!$order->isCancelled())
            @php $steps=['pending'=>1,'preparing'=>2,'ready'=>3,'paid'=>4]; $cur=$steps[$order->status]??0; @endphp
            <div class="mt-3 pt-3 border-t border-gray-50 dark:border-slate-700/50">
                <div class="flex gap-1 mb-1.5">
                    @for($i=1; $i<=4; $i++)
                        <div class="flex-1 h-1 rounded-full {{ $i <= $cur ? 'bg-blue-500' : 'bg-gray-100 dark:bg-slate-700' }}"></div>
                    @endfor
                </div>
                <div class="flex justify-between">
                    @foreach(['En attente','Préparation','Prête','Payée'] as $i => $s)
                        <span class="text-xs {{ ($i+1)<=$cur ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-300 dark:text-slate-600' }}">
                            {{ $s }}
                        </span>
                    @endforeach
                </div>
            </div>
            @else
            <div class="mt-2 pt-2 border-t border-gray-50 dark:border-slate-700/50">
                <span class="text-xs text-red-500">❌ Annulée</span>
            </div>
            @endif
        </a>
        @endforeach
    </div>

    <div class="mt-5">{{ $orders->links() }}</div>
@endif

@endsection