@extends('layouts.client')

@section('title', 'Notre Carte')

@section('content')

{{-- Header --}}
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">🍔 Notre Carte</h1>
    <p class="text-gray-500 mt-1">Découvrez nos burgers faits maison</p>
</div>

{{-- Filtres --}}
<form method="GET" action="{{ route('client.catalog.index') }}"
      class="flex flex-wrap gap-3 mb-8 bg-white p-4 rounded-xl shadow-sm border border-gray-100">

    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Rechercher un burger..."
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
        <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Prix croissant</option>
        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
    </select>

    <button type="submit" class="btn-primary">Filtrer</button>
    <a href="{{ route('client.catalog.index') }}" class="btn-secondary">Réinitialiser</a>
</form>

{{-- Grille des produits --}}
@if($products->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <span class="text-5xl">🔍</span>
        <p class="mt-4 text-lg">Aucun burger trouvé.</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

                {{-- Image --}}
                <div class="h-48 bg-orange-50 flex items-center justify-center overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <span class="text-7xl">🍔</span>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="p-5">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $product->name }}</h3>
                        @if($product->stock <= 5)
                            <span class="badge-cancelled text-xs shrink-0">Stock faible</span>
                        @endif
                    </div>

                    <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $product->description }}</p>

                    <div class="flex items-center justify-between mt-4">
                        <span class="text-xl font-bold text-orange-600">{{ $product->formatted_price }}</span>
                        <a href="{{ route('client.catalog.show', $product->slug) }}"
                           class="btn-primary text-sm py-1.5">
                            Commander
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif

@endsection