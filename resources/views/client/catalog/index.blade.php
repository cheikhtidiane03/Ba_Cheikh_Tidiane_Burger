@extends('layouts.client')

@section('title', 'Notre catalogue de burgers')
@section('page-title', 'Nos Burger')
@section('page-subtitle', 'Burgers artisanaux préparés à la commande')

@section('content')

{{-- ── Filtres ── --}}
<form method="GET" action="{{ route('client.catalog.index') }}"
      class="flex flex-wrap gap-2.5 mb-6 p-4
             bg-white dark:bg-slate-800
             rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">

    {{-- Recherche --}}
    <div class="relative flex-1 min-w-[180px]">
        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 pointer-events-none"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Rechercher un burger..."
               class="form-input pl-9">
    </div>

    {{-- Catégorie --}}
    <select name="category" class="form-input w-44">
        <option value="">Toutes catégories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>

    {{-- Tri --}}
    <select name="sort" class="form-input w-44">
        <option value="">Trier par...</option>
        <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Prix : le moins cher</option>
        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix : le plus cher</option>
    </select>

    <button type="submit" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
        </svg>
        Filtrer
    </button>

    @if(request()->hasAny(['search','category','sort']))
        <a href="{{ route('client.catalog.index') }}"
           class="btn-secondary text-red-500 dark:text-red-400">
            ✕ Réinitialiser
        </a>
    @endif
</form>

{{-- ── Résultats ── --}}
@if($products->isEmpty())
    <div class="text-center py-24 bg-white dark:bg-slate-800
                rounded-2xl border border-dashed border-gray-200 dark:border-slate-700">
        <p class="text-6xl mb-4"></p>
        <p class="font-bold text-lg text-gray-700 dark:text-slate-300 mb-1">Aucun burger trouvé</p>
        <p class="text-sm text-gray-400 dark:text-slate-500 mb-5">Essayez avec d'autres filtres</p>
        <a href="{{ route('client.catalog.index') }}" class="btn-primary">Voir tous les burgers</a>
    </div>
@else

    {{-- Compteur --}}
    <p class="text-xs text-gray-400 dark:text-slate-500 font-medium mb-4">
        {{ $products->total() }} burger{{ $products->total() > 1 ? 's' : '' }} disponible{{ $products->total() > 1 ? 's' : '' }}
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($products as $product)
        <article class="group bg-white dark:bg-slate-800
                        border border-gray-100 dark:border-slate-700
                        rounded-2xl shadow-sm overflow-hidden
                        hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-700/50
                        hover:-translate-y-1 transition-all duration-250">

            {{-- Image --}}
            <div class="relative h-48 bg-gradient-to-br from-orange-50 to-amber-50
                        dark:from-slate-700 dark:to-slate-700/50
                        overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover
                                group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="text-8xl group-hover:scale-110 transition-transform duration-300 drop-shadow-sm">🍔</span>
                    </div>
                @endif

                {{-- Overlay gradient bas --}}
                <div class="absolute inset-x-0 bottom-0 h-16
                            bg-gradient-to-t from-black/30 to-transparent opacity-0
                            group-hover:opacity-100 transition-opacity duration-300"></div>

                {{-- Badge catégorie --}}
                <span class="absolute top-3 left-3
                             bg-white/95 dark:bg-slate-800/95 backdrop-blur-sm
                             text-xs font-bold px-2.5 py-1 rounded-full shadow-sm
                             text-gray-700 dark:text-slate-300">
                    {{ $product->category->name }}
                </span>

                {{-- Badge stock faible --}}
                @if($product->stock <= 5 && $product->stock > 0)
                    <span class="absolute top-3 right-3
                                 bg-amber-500 text-white
                                 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                        Plus que {{ $product->stock }} !
                    </span>
                @endif
            </div>

            {{-- Contenu --}}
            <div class="p-5">
                <div class="mb-3">
                    <h3 class="font-black text-gray-900 dark:text-white text-base mb-1 leading-tight">
                        {{ $product->name }}
                    </h3>
                    <p class="text-xs text-gray-400 dark:text-slate-500 line-clamp-2 leading-relaxed min-h-[32px]">
                        {{ $product->description }}
                    </p>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-50 dark:border-slate-700/50">
                    <div>
                        <p class="font-black text-blue-600 dark:text-blue-400 text-xl leading-none">
                            {{ $product->formatted_price }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">par burger</p>
                    </div>
                    <a href="{{ route('client.catalog.show', $product->slug) }}"
                       class="btn-primary text-xs py-2 px-4 rounded-xl shadow-sm shadow-blue-600/20">
                        Commander
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    <div class="mt-8">{{ $products->links() }}</div>
@endif

@endsection