@extends('layouts.admin')

@section('title', 'Commande ' . $order->reference)
@section('page-title', 'Commande ' . $order->reference)
@section('page-subtitle', 'Passée le ' . $order->created_at->locale('fr')->isoFormat('dddd D MMMM YYYY à HH:mm'))

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <div class="lg:col-span-2 space-y-5">

        {{-- Articles commandés --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700">
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">Articles commandés</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-th">Produit</th>
                            <th class="table-th text-center">Qté</th>
                            <th class="table-th text-right">Prix unit.</th>
                            <th class="table-th text-right">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                        @foreach($order->items as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="table-td">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-orange-50 dark:bg-slate-700 rounded-xl
                                                flex items-center justify-center text-xl flex-shrink-0
                                                overflow-hidden border border-gray-100 dark:border-slate-600">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/'.$item->product->image) }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            
                                        @endif
                                    </div>
                                    <span class="font-semibold text-gray-800 dark:text-white text-sm">
                                        {{ $item->product->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="table-td text-center font-bold text-gray-900 dark:text-white">
                                × {{ $item->quantity }}
                            </td>
                            <td class="table-td text-right text-gray-500 dark:text-slate-400">
                                {{ $item->formatted_unit_price }}
                            </td>
                            <td class="table-td text-right font-bold text-gray-900 dark:text-white">
                                {{ $item->formatted_subtotal }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-blue-50 dark:bg-blue-500/10">
                            <td colspan="3" class="table-td text-right font-bold text-blue-900 dark:text-blue-300">
                                Total
                            </td>
                            <td class="table-td text-right font-black text-blue-600 dark:text-blue-400 text-lg">
                                {{ $order->formatted_total }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Notes du client --}}
        @if($order->notes)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-2">📝 Notes du client</h3>
            <p class="text-sm text-gray-600 dark:text-slate-400
                       bg-gray-50 dark:bg-slate-700/50 rounded-xl p-3">
                {{ $order->notes }}
            </p>
        </div>
        @endif

        {{-- Paiement enregistré --}}
        @if($order->payment)
        <div class="bg-emerald-50 dark:bg-emerald-500/10
                    border border-emerald-200 dark:border-emerald-500/30
                    rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-500/20
                            rounded-xl flex items-center justify-center text-xl">
                    
                </div>
                <div>
                    <h3 class="font-bold text-emerald-800 dark:text-emerald-400 text-sm">
                        Paiement enregistré
                    </h3>
                    <p class="text-xs text-emerald-600 dark:text-emerald-500">
                        Par {{ $order->payment->recorder->name }}
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider
                               text-emerald-600 dark:text-emerald-500 mb-1">
                        Montant
                    </p>
                    <p class="font-black text-emerald-700 dark:text-emerald-400 text-xl">
                        {{ $order->payment->formatted_amount }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider
                               text-emerald-600 dark:text-emerald-500 mb-1">
                        Mode
                    </p>
                    <p class="font-semibold text-emerald-800 dark:text-emerald-300 text-sm">
                        Espèces
                    </p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider
                               text-emerald-600 dark:text-emerald-500 mb-1">
                        Date & heure
                    </p>
                    <p class="font-semibold text-emerald-800 dark:text-emerald-300 text-sm">
                        {{ $order->payment->paid_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
            </div>
            @if($order->payment->notes)
                <p class="mt-3 text-xs text-emerald-600 dark:text-emerald-500
                           bg-emerald-100 dark:bg-emerald-500/20 rounded-lg px-3 py-2">
                    📝 {{ $order->payment->notes }}
                </p>
            @endif
        </div>
        @endif

    </div>


    <div class="space-y-4">

        {{-- Statut & Actions --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-4">Statut de la commande</h3>

            @php
                $steps = [
                    ['key' => 'pending',   'label' => 'En attente',     'icon' => ''],
                    ['key' => 'preparing', 'label' => 'En préparation', 'icon' => ''],
                    ['key' => 'ready',     'label' => 'Prête',          'icon' => ''],
                    ['key' => 'paid',      'label' => 'Payée',          'icon' => ''],
                ];
                $statusOrder = ['pending' => 0, 'preparing' => 1, 'ready' => 2, 'paid' => 3, 'cancelled' => -1];
                $currentIdx  = $statusOrder[$order->status] ?? -1;
            @endphp

            {{-- Timeline --}}
            @if($order->isCancelled())
                <div class="flex items-center gap-3 bg-red-50 dark:bg-red-500/10
                            border border-red-200 dark:border-red-500/30
                            text-red-700 dark:text-red-400
                            px-4 py-3 rounded-xl text-sm mb-4">
                    ❌ Commande annulée
                </div>
            @else
                <div class="space-y-2 mb-5">
                    @foreach($steps as $i => $step)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0
                                    {{ $i < $currentIdx  ? 'bg-blue-600 text-white'
                                       : ($i === $currentIdx ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 ring-2 ring-blue-300 dark:ring-blue-500/50'
                                       : 'bg-gray-100 dark:bg-slate-700 text-gray-300 dark:text-slate-600') }}">
                            @if($i < $currentIdx) ✓ @else {{ $step['icon'] }} @endif
                        </div>
                        <span class="text-sm {{ $i <= $currentIdx
                            ? 'font-semibold text-gray-900 dark:text-white'
                            : 'text-gray-400 dark:text-slate-600' }}">
                            {{ $step['label'] }}
                        </span>
                        @if($i === $currentIdx)
                            <span class="ml-auto text-xs bg-blue-100 dark:bg-blue-500/20
                                         text-blue-700 dark:text-blue-400
                                         px-2 py-0.5 rounded-full font-bold">
                                Actuel
                            </span>
                        @endif
                    </div>
                    @if($i < count($steps) - 1)
                        <div class="ml-4 w-0.5 h-3 rounded
                                    {{ $i < $currentIdx
                                        ? 'bg-blue-300 dark:bg-blue-600'
                                        : 'bg-gray-200 dark:bg-slate-700' }}">
                        </div>
                    @endif
                    @endforeach
                </div>
            @endif

            {{-- Boutons transitions (pending → preparing → ready) --}}
            @if(!$order->isCancelled() && !$order->isPaid())
                <div class="space-y-2">

                    @php
                        $nextStatuses = [
                            'pending'   => [['key' => 'preparing', 'label' => 'Mettre en préparation', 'class' => 'btn-primary']],
                            'preparing' => [['key' => 'ready',     'label' => ' Marquer comme prête',     'class' => 'btn-success']],
                        ];
                        $buttons = $nextStatuses[$order->status] ?? [];
                    @endphp

                    @foreach($buttons as $btn)
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $btn['key'] }}">
                            @if($btn['key'] === 'ready')
                                <div class="flex items-start gap-2 text-xs text-amber-700 dark:text-amber-400
                                            bg-amber-50 dark:bg-amber-500/10
                                            border border-amber-200 dark:border-amber-500/30
                                            px-3 py-2 rounded-xl mb-2">
                                    <span class="flex-shrink-0">📧</span>
                                    <span>Un email avec la facture PDF sera automatiquement envoyé au client.</span>
                                </div>
                            @endif
                            <button type="submit" class="{{ $btn['class'] }} w-full justify-center">
                                {{ $btn['label'] }}
                            </button>
                        </form>
                    @endforeach

                    {{-- ══ FORMULAIRE PAIEMENT (uniquement si ready) ══ --}}
                    @if($order->isReady() && !$order->payment)
                        <div class="bg-emerald-50 dark:bg-emerald-500/10
                                    border border-emerald-200 dark:border-emerald-500/30
                                    rounded-xl p-4 mt-1">
                            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400 mb-3 flex items-center gap-2">
                                <span></span> Enregistrer le paiement en espèces
                            </p>
                            <form method="POST" action="{{ route('admin.payments.store', $order) }}">
                                @csrf
                                <div class="space-y-3">
                                    <div>
                                        <label class="form-label">Montant reçu (FCFA)</label>
                                        <input type="number" name="amount"
                                               value="{{ $order->total_amount }}"
                                               min="0" step="50"
                                               class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Notes <span class="font-normal normal-case opacity-60">(facultatif)</span></label>
                                        <input type="text" name="notes"
                                               placeholder="Ex: rendu monnaie 500 FCFA"
                                               class="form-input">
                                    </div>
                                    <button type="submit" class="btn-success w-full justify-center">
                                        ✅ Confirmer le paiement
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- Annuler --}}
                    <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                          id="cancel-form-{{ $order->id }}">
                        @csrf @method('PATCH')
                    </form>
                    <button onclick="triggerConfirm(
                                'Annuler cette commande ?',
                                '{{ addslashes($order->reference) }} sera définitivement annulée.',
                                'delete',
                                'Annuler la commande',
                                'cancel-form-{{ $order->id }}')"
                            class="btn-danger w-full justify-center">
                        ❌ Annuler la commande
                    </button>

                </div>
            @endif
        </div>

        {{-- Infos client --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-4">Informations client</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 bg-blue-100 dark:bg-blue-500/20 rounded-xl
                            flex items-center justify-center font-bold
                            text-blue-600 dark:text-blue-400 text-sm flex-shrink-0">
                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-800 dark:text-white text-sm truncate">
                        {{ $order->user->name }}
                    </p>
                    <p class="text-gray-400 dark:text-slate-500 text-xs truncate">
                        {{ $order->user->email }}
                    </p>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-slate-700/50 rounded-xl p-3 text-sm flex justify-between">
                <span class="text-gray-400 dark:text-slate-500">Total commandes</span>
                <span class="font-bold text-gray-800 dark:text-white">
                    {{ $order->user->orders->count() }}
                </span>
            </div>
        </div>

        {{-- Résumé financier --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">Résumé</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400 dark:text-slate-500">Référence</span>
                    <span class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400">
                        {{ $order->reference }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400 dark:text-slate-500">Articles</span>
                    <span class="font-semibold text-gray-700 dark:text-slate-300">
                        {{ $order->items->count() }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400 dark:text-slate-500">Statut</span>
                    <span class="badge-{{ $order->status }}">{{ $order->status_label }}</span>
                </div>
                <div class="border-t border-gray-100 dark:border-slate-700 pt-2 flex justify-between">
                    <span class="font-bold text-gray-700 dark:text-slate-300">Total</span>
                    <span class="font-black text-blue-600 dark:text-blue-400">
                        {{ $order->formatted_total }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Retour --}}
        <a href="{{ route('admin.orders.index') }}" class="btn-secondary w-full justify-center">
            ← Retour aux commandes
        </a>

    </div>
</div>

@endsection

@push('scripts')
<script>
/* Réutilise le confirmModal du layout admin */
function triggerConfirm(title, msg, type, label, formId) {
    if (typeof window.triggerConfirm === 'function') {
        window.triggerConfirm(title, msg, type, label, formId);
        return;
    }
    // Fallback si le modal n'est pas disponible
    if (confirm(title + '\n' + msg)) {
        document.getElementById(formId).submit();
    }
}
</script>
@endpush