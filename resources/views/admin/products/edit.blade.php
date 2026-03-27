@extends('layouts.admin')

@section('title', 'Modifier : ' . $product->name)
@section('page-title', 'Modifier : ' . $product->name)
@section('page-subtitle', 'Modifier les informations du burger')

@section('content')
<div class="mt-6 max-w-2xl">

    <form method="POST"
          action="{{ route('admin.products.update', $product) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card space-y-5">

            {{-- Nom --}}
            <div>
                <label class="form-label">Nom du burger <span class="text-red-500">*</span></label>
                <input type="text" name="name"
                       value="{{ old('name', $product->name) }}"
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
                            {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
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
                          class="form-input @error('description') border-red-400 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Prix & Stock --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Prix (FCFA) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" min="0" step="50"
                           value="{{ old('price', $product->price) }}"
                           class="form-input @error('price') border-red-400 @enderror">
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Stock <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" min="0"
                           value="{{ old('stock', $product->stock) }}"
                           class="form-input @error('stock') border-red-400 @enderror">
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Image actuelle --}}
            <div>
                <label class="form-label">Image</label>
                @if($product->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-400 mt-1">Uploader une nouvelle image pour remplacer</p>
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
                       {{ old('is_available', $product->is_available) ? 'checked' : '' }}
                       class="w-4 h-4 text-orange-600 rounded border-gray-300">
                <label for="is_available" class="text-sm text-gray-700">
                    Visible dans le catalogue client
                </label>
            </div>

        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Enregistrer les modifications
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Annuler</a>
        </div>

    </form>
</div>
@endsection