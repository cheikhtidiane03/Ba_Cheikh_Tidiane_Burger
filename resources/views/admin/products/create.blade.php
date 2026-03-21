@extends('layouts.admin')

@section('title', isset($product->id) ? 'Modifier le burger' : 'Nouveau burger')
@section('page-title', isset($product->id) ? 'Modifier : ' . $product->name : 'Ajouter un burger')
@section('page-subtitle', isset($product->id) ? 'Modifier les informations du burger' : 'Créer un nouveau burger dans le catalogue')

@section('content')
<div class="mt-6 max-w-2xl">

    <form method="POST"
          action="{{ isset($product->id) ? route('admin.products.update', $product) : route('admin.products.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if(isset($product->id)) @method('PUT') @endif

        <div class="card space-y-5">

            {{-- Nom --}}
            <div>
                <label class="form-label">Nom du burger <span class="text-red-500">*</span></label>
                <input type="text" name="name"
                       value="{{ old('name', $product->name ?? '') }}"
                       placeholder="Ex : ISI Classic"
                       class="form-input @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Catégorie --}}
            <div>
                <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
                <select name="category_id"
                        class="form-input @error('category_id') border-red-400 @enderror">
                    <option value="">Sélectionner une catégorie</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="3"
                          placeholder="Ingrédients, description du burger..."
                          class="form-input @error('description') border-red-400 @enderror">{{ old('description', $product->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Prix & Stock --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Prix (FCFA) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" min="0" step="50"
                           value="{{ old('price', $product->price ?? '') }}"
                           placeholder="2500"
                           class="form-input @error('price') border-red-400 @enderror">
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Stock <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" min="0"
                           value="{{ old('stock', $product->stock ?? 0) }}"
                           class="form-input @error('stock') border-red-400 @enderror">
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Image --}}
            <div>
                <label class="form-label">Image du burger</label>

                @if(isset($product->id) && $product->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-400 mt-1">Image actuelle — en uploader une nouvelle la remplacera</p>
                    </div>
                @endif

                <input type="file" name="image" accept="image/*"
                       class="form-input @error('image') border-red-400 @enderror">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — max 2MB</p>
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Disponibilité --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_available" id="is_available" value="1"
                       {{ old('is_available', $product->is_available ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 text-orange-600 rounded border-gray-300">
                <label for="is_available" class="text-sm text-gray-700">
                    Visible dans le catalogue client
                </label>
            </div>

        </div>

        {{-- Boutons --}}
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ isset($product->id) ? 'Enregistrer les modifications' : 'Créer le burger' }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Annuler</a>
        </div>

    </form>
</div>
@endsection