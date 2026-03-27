@extends('layouts.client')

@section('title', 'Commande ' . $order->reference)
@section('page-title', 'Commande ' . $order->reference)
@section('page-subtitle', 'Passée le ' . $order->created_at->locale('fr')->isoFormat('D MMM YYYY à HH:mm'))

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Colonne principale --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Articles --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700">
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">Articles commandés</h3>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-slate-700/50">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 px-5 py-3.5">
                    <div class="w-11 h-11 bg-orange-50 dark:bg-slate-700 rounded-xl flex items-center
                                justify-center text-xl flex-shrink-0 overflow-hidden border
                                border-gray-100 dark:border-slate-600">
                        @if($item->product->image)
                            <img src="{{ asset('storage/'.$item->product->image) }}"
                                 class="w-full h-full object-cover">
                        @else
                            🍔
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $item->product->name }}</p>
                        <p class="text-xs text-gray-400 dark:text-slate-500">
                            {{ $item->formatted_unit_price }} × {{ $item->quantity }}
                        </p>
                    </div>
                    <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $item->formatted_subtotal }}</p>
                </div>
                @endforeach
            </div>
            <div class="px-5 py-3.5 bg-blue-50 dark:bg-blue-500/10 border-t border-blue-100 dark:border-blue-500/20 flex justify-between items-center">
                <span class="font-bold text-blue-900 dark:text-blue-300 text-sm">Total</span>
                <span class="font-black text-blue-600 dark:text-blue-400 text-xl">{{ $order->formatted_total }}</span>
            </div>
        </div>

        {{-- Notes --}}
        @if($order->notes)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white mb-2">Vos notes</h3>
            <p class="text-sm text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/50 rounded-xl p-3">
                {{ $order->notes }}
            </p>
        </div>
        @endif

        {{-- Paiement --}}
        @if($order->payment)
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 bg-emerald-100 dark:bg-emerald-500/20 rounded-xl flex items-center justify-center text-lg">💳</div>
                <div>
                    <p class="font-bold text-emerald-800 dark:text-emerald-400 text-sm">Paiement confirmé</p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-500">{{ $order->payment->paid_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            <p class="font-black text-2xl text-emerald-700 dark:text-emerald-400">{{ $order->payment->formatted_amount }}</p>
        </div>
        @endif

    </div>

    {{-- Colonne droite --}}
    <div class="space-y-4">

        {{-- Statut --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white mb-4">Suivi de commande</h3>

            @if($order->isCancelled())
                <div class="text-center py-4">
                    <p class="text-3xl mb-1">❌</p>
                    <p class="font-bold text-red-600 dark:text-red-400 text-sm">Commande annulée</p>
                </div>
            @else
                @php
                    $steps = [
                        ['key' => 'pending',   'label' => 'En attente',     'icon' => '⏳'],
                        ['key' => 'preparing', 'label' => 'En préparation', 'icon' => '👨‍🍳'],
                        ['key' => 'ready',     'label' => 'Prête',          'icon' => '✅'],
                        ['key' => 'paid',      'label' => 'Payée',          'icon' => '💰'],
                    ];
                    $vals = ['pending'=>0,'preparing'=>1,'ready'=>2,'paid'=>3];
                    $cur  = $vals[$order->status] ?? -1;
                @endphp
                <div class="space-y-2.5">
                    @foreach($steps as $i => $step)
                    <div class="flex items-start gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0
                                        {{ $i < $cur  ? 'bg-blue-600 text-white'
                                           : ($i === $cur ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 ring-2 ring-blue-300 dark:ring-blue-500/50'
                                           : 'bg-gray-100 dark:bg-slate-700 text-gray-300 dark:text-slate-600') }}">
                                @if($i < $cur) ✓ @else {{ $step['icon'] }} @endif
                            </div>
                            @if($i < count($steps)-1)
                                <div class="w-0.5 h-4 mt-0.5 rounded {{ $i < $cur ? 'bg-blue-300 dark:bg-blue-600' : 'bg-gray-100 dark:bg-slate-700' }}"></div>
                            @endif
                        </div>
                        <div class="pt-1.5">
                            <p class="text-sm font-semibold {{ $i <= $cur ? 'text-gray-900 dark:text-white' : 'text-gray-300 dark:text-slate-600' }}">
                                {{ $step['label'] }}
                                @if($i === $cur)
                                    <span class="text-xs text-blue-500 font-bold ml-1">← Actuel</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Résumé --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5 space-y-2.5">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400 dark:text-slate-500">Référence</span>
                <span class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400">{{ $order->reference }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400 dark:text-slate-500">Date</span>
                <span class="font-medium text-gray-700 dark:text-slate-300">{{ $order->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400 dark:text-slate-500">Articles</span>
                <span class="font-medium text-gray-700 dark:text-slate-300">{{ $order->items->count() }}</span>
            </div>
            <div class="border-t border-gray-50 dark:border-slate-700 pt-2 flex justify-between text-sm">
                <span class="font-bold text-gray-700 dark:text-slate-300">Total</span>
                <span class="font-black text-blue-600 dark:text-blue-400">{{ $order->formatted_total }}</span>
            </div>
        </div>

        <div class="space-y-2">
            <a href="{{ route('client.catalog.index') }}" class="btn-primary w-full justify-center">
                🍔 Commander à nouveau
            </a>
            <a href="{{ route('client.orders.index') }}" class="btn-secondary w-full justify-center">
                ← Mes commandes
            </a>
        </div>

    </div>
</div>

@endsection