@extends('layouts.client')
@section('title', 'Notre Carte — ISI BURGER')

@section('content')

{{-- Hero --}}
<div class="mb-8">
    <h1 class="text-4xl font-black text-gray-900 mb-1" style="font-family:'Syne',sans-serif">
        🍔 Notre Carte
    </h1>
    <p class="text-gray-500">Burgers artisanaux préparés à la commande</p>
</div>

{{-- Filtres --}}
<form method="GET" action="{{ route('client.catalog.index') }}"
      class="flex flex-wrap gap-3 mb-8 p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="🔍 Rechercher un burger..."
           class="form-input max-w-xs">
    <select name="category" class="form-input max-w-xs">
        <option value="">Toutes les catégories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    <select name="sort" class="form-input max-w-xs">
        <option value="">Trier par...</option>
        <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Prix ↑ croissant</option>
        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix ↓ décroissant</option>
    </select>
    <button type="submit" class="btn-primary">Filtrer</button>
    <a href="{{ route('client.catalog.index') }}" class="btn-secondary">Réinitialiser</a>
</form>

{{-- Grille produits --}}
@if($products->isEmpty())
    <div class="text-center py-20">
        <p class="text-6xl mb-4">🔍</p>
        <p class="text-gray-400 text-lg">Aucun burger trouvé.</p>
        <a href="{{ route('client.catalog.index') }}" class="btn-primary mt-4 inline-flex">
            Voir tous les burgers
        </a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
        <div class="card-hover group overflow-hidden p-0">
            {{-- Image --}}
            <div class="h-52 bg-amber-50 flex items-center justify-center overflow-hidden relative">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <span class="text-8xl group-hover:scale-110 transition-transform duration-300">🍔</span>
                @endif

                {{-- Badge catégorie --}}
                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                    {{ $product->category->name }}
                </span>

                {{-- Badge stock faible --}}
                @if($product->stock <= 5 && $product->stock > 0)
                    <span class="absolute top-3 right-3 bg-orange-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                        Plus que {{ $product->stock }} !
                    </span>
                @endif
            </div>

            {{-- Infos --}}
            <div class="p-5">
                <h3 class="font-bold text-gray-900 text-lg mb-1" style="font-family:'Syne',sans-serif">
                    {{ $product->name }}
                </h3>
                <p class="text-gray-500 text-sm line-clamp-2 mb-4">{{ $product->description }}</p>

                <div class="flex items-center justify-between">
                    <span class="text-2xl font-black text-amber-500" style="font-family:'Syne',sans-serif">
                        {{ $product->formatted_price }}
                    </span>
                    <a href="{{ route('client.catalog.show', $product->slug) }}"
                       class="btn-primary text-sm py-2 px-4">
                        Commander
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-10 flex justify-center">
        {{ $products->links() }}
    </div>
@endif

@endsection