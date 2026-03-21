@extends('layouts.admin')

@section('title', 'Commande ' . $order->reference)
@section('page-title', 'Commande ' . $order->reference)
@section('page-subtitle', 'Passée le ' . $order->created_at->locale('fr')->isoFormat('dddd D MMMM YYYY à HH:mm'))

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ===== COLONNE PRINCIPALE ===== --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Articles commandés --}}
        <div class="card p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Articles commandés</h3>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="table-th">Produit</th>
                        <th class="table-th text-center">Qté</th>
                        <th class="table-th text-right">Prix unit.</th>
                        <th class="table-th text-right">Sous-total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="table-td">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-xl">
                                    🍔
                                </div>
                                <span class="font-semibold text-gray-800">{{ $item->product->name }}</span>
                            </div>
                        </td>
                        <td class="table-td text-center font-bold">{{ $item->quantity }}</td>
                        <td class="table-td text-right text-gray-500">{{ $item->formatted_unit_price }}</td>
                        <td class="table-td text-right font-bold text-gray-900">{{ $item->formatted_subtotal }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="table-td text-right font-bold text-gray-700">Total</td>
                        <td class="table-td text-right font-black text-blue-600 text-lg">
                            {{ $order->formatted_total }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Notes --}}
        @if($order->notes)
        <div class="card">
            <h3 class="font-bold text-gray-900 mb-2">Notes du client</h3>
            <p class="text-gray-600 text-sm bg-gray-50 rounded-xl p-3">{{ $order->notes }}</p>
        </div>
        @endif

        {{-- Paiement enregistré --}}
        @if($order->payment)
        <div class="card border-l-4 border-emerald-500">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-xl">💳</div>
                <div>
                    <h3 class="font-bold text-gray-900">Paiement enregistré</h3>
                    <p class="text-xs text-gray-400">Par {{ $order->payment->recorder->name }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase">Montant</p>
                    <p class="font-black text-emerald-600 text-lg">{{ $order->payment->formatted_amount }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase">Mode</p>
                    <p class="font-semibold text-gray-800">Espèces</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase">Date</p>
                    <p class="font-semibold text-gray-800">{{ $order->payment->paid_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- ===== COLONNE DROITE ===== --}}
    <div class="space-y-5">

        {{-- Statut actuel --}}
        <div class="card">
            <h3 class="font-bold text-gray-900 mb-4">Statut de la commande</h3>

            {{-- Timeline statuts --}}
            @php
                $steps = [
                    ['key' => 'pending',   'label' => 'En attente',    'icon' => '⏳'],
                    ['key' => 'preparing', 'label' => 'En préparation', 'icon' => '👨‍🍳'],
                    ['key' => 'ready',     'label' => 'Prête',          'icon' => '✅'],
                    ['key' => 'paid',      'label' => 'Payée',          'icon' => '💰'],
                ];
                $statusOrder = ['pending' => 0, 'preparing' => 1, 'ready' => 2, 'paid' => 3, 'cancelled' => -1];
                $currentIdx  = $statusOrder[$order->status] ?? -1;
            @endphp

            @if($order->isCancelled())
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    ❌ Commande annulée
                </div>
            @else
                <div class="space-y-2 mb-5">
                    @foreach($steps as $i => $step)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm flex-shrink-0
                                    {{ $i <= $currentIdx ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400' }}">
                            @if($i < $currentIdx)
                                ✓
                            @else
                                {{ $step['icon'] }}
                            @endif
                        </div>
                        <span class="text-sm {{ $i <= $currentIdx ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                            {{ $step['label'] }}
                        </span>
                        @if($i === $currentIdx)
                            <span class="ml-auto text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">
                                Actuel
                            </span>
                        @endif
                    </div>
                    @if($i < count($steps) - 1)
                        <div class="ml-4 w-0.5 h-3 {{ $i < $currentIdx ? 'bg-blue-300' : 'bg-gray-200' }}"></div>
                    @endif
                    @endforeach
                </div>
            @endif

            {{-- Boutons de transition --}}
            @if(!$order->isCancelled() && !$order->isPaid())
                <div class="space-y-2">
                    @php
                        $nextStatuses = [
                            'pending'   => [['key' => 'preparing', 'label' => '👨‍🍳 Mettre en préparation', 'class' => 'btn-primary']],
                            'preparing' => [['key' => 'ready',     'label' => '✅ Marquer comme prête',     'class' => 'btn-success']],
                            'ready'     => [['key' => 'paid',      'label' => '💰 Enregistrer paiement',    'class' => 'btn-warning']],
                        ];
                        $buttons = $nextStatuses[$order->status] ?? [];
                    @endphp

                    @foreach($buttons as $btn)
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $btn['key'] }}">
                            @if($btn['key'] === 'ready')
                                <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 px-3 py-2 rounded-xl mb-2">
                                    📧 Un email avec la facture PDF sera envoyé au client.
                                </p>
                            @endif
                            <button type="submit" class="{{ $btn['class'] }} w-full justify-center">
                                {{ $btn['label'] }}
                            </button>
                        </form>
                    @endforeach

                    @if(!$order->isPaid())
                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                              onsubmit="return confirm('Annuler cette commande ?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-danger w-full justify-center">
                                ❌ Annuler la commande
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        {{-- Infos client --}}
        <div class="card">
            <h3 class="font-bold text-gray-900 mb-4">Informations client</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center font-bold text-blue-600">
                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $order->user->name }}</p>
                    <p class="text-gray-400 text-sm">{{ $order->user->email }}</p>
                </div>
            </div>
            <div class="text-sm text-gray-500 bg-gray-50 rounded-xl p-3">
                <p>Total commandes : <span class="font-semibold text-gray-800">{{ $order->user->orders->count() }}</span></p>
            </div>
        </div>

        {{-- Retour --}}
        <a href="{{ route('admin.orders.index') }}" class="btn-secondary w-full justify-center">
            ← Retour aux commandes
        </a>

    </div>
</div>

@endsection