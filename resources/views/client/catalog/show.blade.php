@extends('layouts.client')

@section('title', $product->name . ' — ISI BURGER')
@section('page-title', $product->name)
@section('page-subtitle', $product->category->name)

@section('content')

{{-- Fil d'ariane --}}
<nav class="flex items-center gap-1.5 text-xs mb-6">
    <a href="{{ route('client.catalog.index') }}"
       class="text-gray-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">
        Notre Carte
    </a>
    <svg class="w-3 h-3 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-gray-700 dark:text-slate-300 font-semibold truncate">{{ $product->name }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-4xl">

    {{-- Image --}}
    <div class="aspect-square rounded-2xl overflow-hidden border border-gray-100 dark:border-slate-700 shadow-sm
                bg-gradient-to-br from-orange-50 to-amber-50 dark:from-slate-800 dark:to-slate-700
                flex items-center justify-center">
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover">
        @else
            <span class="text-[120px]">🍔</span>
        @endif
    </div>

    {{-- Infos --}}
    <div class="flex flex-col justify-between py-2">

        <div>
            {{-- Catégorie --}}
            <span class="inline-flex items-center bg-blue-50 dark:bg-blue-500/10
                         text-blue-700 dark:text-blue-400
                         text-xs font-bold px-3 py-1 rounded-full mb-4">
                {{ $product->category->name }}
            </span>

            <h1 class="text-2xl font-black text-gray-900 dark:text-white mb-3 leading-tight">
                {{ $product->name }}
            </h1>

            <p class="text-sm text-gray-500 dark:text-slate-400 leading-relaxed mb-5">
                {{ $product->description }}
            </p>

            {{-- Prix --}}
            <div class="flex items-baseline gap-2 mb-6">
                <span class="text-4xl font-black text-blue-600 dark:text-blue-400">
                    {{ $product->formatted_price }}
                </span>
                <span class="text-sm text-gray-400 dark:text-slate-500">par burger</span>
            </div>

            {{-- Alertes stock --}}
            @if($product->stock <= 0)
                <div class="flex items-center gap-3 bg-red-50 dark:bg-red-500/10
                            border border-red-200 dark:border-red-500/30
                            text-red-700 dark:text-red-400
                            px-4 py-3 rounded-xl text-sm mb-5">
                    <span class="text-lg flex-shrink-0">❌</span>
                    <div>
                        <p class="font-bold">Rupture de stock</p>
                        <p class="text-xs opacity-75">Ce burger n'est plus disponible pour le moment</p>
                    </div>
                </div>
            @elseif($product->stock <= 5)
                <div class="flex items-center gap-3 bg-amber-50 dark:bg-amber-500/10
                            border border-amber-200 dark:border-amber-500/30
                            text-amber-700 dark:text-amber-400
                            px-4 py-3 rounded-xl text-sm mb-5">
                    <span class="text-lg flex-shrink-0">⚠️</span>
                    <div>
                        <p class="font-bold">Stock limité</p>
                        <p class="text-xs opacity-75">Plus que {{ $product->stock }} disponibles — commandez vite !</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Formulaire commande --}}
        @if($product->isOrderable())
            <form method="POST" action="{{ route('client.orders.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">

                {{-- Quantité --}}
                <div>
                    <label class="form-label">Quantité</label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="changeQty(-1)"
                                class="w-10 h-10 rounded-xl border border-gray-200 dark:border-slate-600
                                       flex items-center justify-center text-xl font-bold
                                       text-gray-500 dark:text-slate-400
                                       hover:bg-gray-50 dark:hover:bg-slate-700
                                       hover:border-gray-300 dark:hover:border-slate-500
                                       transition-all active:scale-95">
                            −
                        </button>
                        <input type="number" name="items[0][quantity]" id="qty"
                               value="1" min="1" max="{{ $product->stock }}"
                               class="form-input w-20 text-center font-bold text-lg py-2">
                        <button type="button" onclick="changeQty(1)"
                                class="w-10 h-10 rounded-xl border border-gray-200 dark:border-slate-600
                                       flex items-center justify-center text-xl font-bold
                                       text-gray-500 dark:text-slate-400
                                       hover:bg-gray-50 dark:hover:bg-slate-700
                                       hover:border-gray-300 dark:hover:border-slate-500
                                       transition-all active:scale-95">
                            +
                        </button>
                        <span class="text-sm text-gray-400 dark:text-slate-500 ml-1">
                            / {{ $product->stock }} disponibles
                        </span>
                    </div>
                </div>

                {{-- Notes --}}
                <div>
                    <label class="form-label">Instructions spéciales <span class="font-normal normal-case opacity-60">(facultatif)</span></label>
                    <textarea name="notes" rows="2"
                              placeholder="Sans oignon, extra sauce, allergie..."
                              class="form-input resize-none"></textarea>
                </div>

                {{-- Total dynamique --}}
                <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20
                            rounded-xl px-4 py-3 flex items-center justify-between">
                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-400">Total estimé</span>
                    <span id="totalPrice" class="font-black text-lg text-blue-700 dark:text-blue-400">
                        {{ $product->formatted_price }}
                    </span>
                </div>

                <button type="submit"
                        class="btn-primary w-full justify-center py-3 text-base shadow-md shadow-blue-600/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Passer la commande
                </button>
            </form>
        @else
            <button disabled
                    class="btn-primary w-full justify-center py-3 opacity-40 cursor-not-allowed">
                Indisponible pour le moment
            </button>
        @endif

        <a href="{{ route('client.catalog.index') }}"
           class="btn-secondary justify-center mt-3 text-sm">
            ← Retour à la carte
        </a>
    </div>
</div>

@push('scripts')
<script>
const unitPrice = {{ $product->price }};

function changeQty(delta) {
    const input  = document.getElementById('qty');
    const newVal = Math.max(1, Math.min(parseInt(input.max), parseInt(input.value) + delta));
    input.value  = newVal;
    updateTotal(newVal);
}

document.getElementById('qty')?.addEventListener('input', function() {
    updateTotal(parseInt(this.value) || 1);
});

function updateTotal(qty) {
    const total = unitPrice * qty;
    document.getElementById('totalPrice').textContent =
        new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';
}
</script>
@endpush

@endsection