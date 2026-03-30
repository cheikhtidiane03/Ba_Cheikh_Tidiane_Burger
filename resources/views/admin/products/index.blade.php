@extends('layouts.admin')

@section('title', 'Produits')
@section('page-title', 'Produits')
@section('page-subtitle', 'Gérez votre catalogue de burgers')

@section('content')

<div class="space-y-5">

{{-- ── Barre d'actions ── --}}
<div class="flex flex-wrap items-center justify-between gap-3">

    <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap gap-2">
        <div class="relative">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher un burger..."
                   class="form-input pl-9 w-56">
        </div>

        <select name="category" class="form-input w-44">
            <option value="">Toutes catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-secondary">Filtrer</button>
        @if(request()->hasAny(['search','category']))
            <a href="{{ route('admin.products.index') }}" class="btn-secondary text-red-500">✕ Reset</a>
        @endif
    </form>

    <button onclick="openModal()"
            class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau burger
    </button>
</div>

{{-- ── Tableau ── --}}
<div class="rounded-2xl border overflow-hidden
            bg-white border-gray-100 shadow-sm
            dark:bg-slate-800 dark:border-slate-700">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-slate-900/60 border-b border-gray-100 dark:border-slate-700">
                <tr>
                    <th class="table-th">Produit</th>
                    <th class="table-th">Catégorie</th>
                    <th class="table-th">Prix</th>
                    <th class="table-th">Stock</th>
                    <th class="table-th">Statut</th>
                    <th class="table-th text-right pr-5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/40 transition-colors duration-150">

                    {{-- Produit --}}
                    <td class="table-td">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl overflow-hidden flex-shrink-0
                                        border border-gray-100 dark:border-slate-600
                                        bg-orange-50 dark:bg-slate-700">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xl">🍔</div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-sm text-gray-900 dark:text-white truncate max-w-[180px]">
                                    {{ $product->name }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-slate-500 truncate max-w-[180px]">
                                    {{ $product->description }}
                                </p>
                            </div>
                        </div>
                    </td>

                    {{-- Catégorie --}}
                    <td class="table-td">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                     bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                            {{ $product->category->name }}
                        </span>
                    </td>

                    {{-- Prix --}}
                    <td class="table-td font-bold text-gray-900 dark:text-white">
                        {{ $product->formatted_price }}
                    </td>

                    {{-- Stock --}}
                    <td class="table-td">
                        <div class="flex items-center gap-2">
                            <div class="w-14 h-1.5 rounded-full bg-gray-100 dark:bg-slate-700 overflow-hidden">
                                @php $pct = min($product->stock, 100); @endphp
                                <div class="h-full rounded-full
                                            {{ $product->stock <= 0 ? 'bg-red-500' : ($product->stock <= 5 ? 'bg-amber-400' : 'bg-emerald-400') }}"
                                     style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="text-sm font-semibold
                                         {{ $product->stock <= 0 ? 'text-red-500' : ($product->stock <= 5 ? 'text-amber-500' : 'text-gray-700 dark:text-slate-300') }}">
                                {{ $product->stock }}
                            </span>
                        </div>
                    </td>

                    {{-- Statut --}}
                    <td class="table-td">
                        @if($product->is_archived)
                            <span class="badge-cancelled">Archivé</span>
                        @elseif($product->stock <= 0)
                            <span class="badge-cancelled">Rupture</span>
                        @elseif(!$product->is_available)
                            <span class="badge-pending">Indisponible</span>
                        @else
                            <span class="badge-ready">Disponible</span>
                        @endif
                    </td>

                    {{-- ── ACTIONS toujours visibles ── --}}
                    <td class="table-td pr-5">
                        <div class="flex items-center justify-end gap-1">

                            {{-- Modifier --}}
                            <button onclick='openModal(@json($product))'
                                    title="Modifier"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                           bg-blue-50 text-blue-700 hover:bg-blue-100
                                           dark:bg-blue-500/15 dark:text-blue-400 dark:hover:bg-blue-500/25
                                           transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Modifier
                            </button>

                            {{-- Archiver --}}
                            <form method="POST" action="{{ route('admin.products.toggle-archive', $product) }}"
                                  id="arch-{{ $product->id }}">@csrf @method('PATCH')</form>
                            <button onclick="triggerConfirm(
                                        '{{ $product->is_archived ? 'Désarchiver' : 'Archiver' }} ce burger ?',
                                        '{{ addslashes($product->name) }}',
                                        'warning',
                                        '{{ $product->is_archived ? 'Désarchiver' : 'Archiver' }}',
                                        'arch-{{ $product->id }}')"
                                    title="{{ $product->is_archived ? 'Désarchiver' : 'Archiver' }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                           bg-amber-50 text-amber-700 hover:bg-amber-100
                                           dark:bg-amber-500/15 dark:text-amber-400 dark:hover:bg-amber-500/25
                                           transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12"/>
                                </svg>
                                {{ $product->is_archived ? 'Désarchiver' : 'Archiver' }}
                            </button>

                            {{-- Supprimer --}}
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  id="del-{{ $product->id }}">@csrf @method('DELETE')</form>
                            <button onclick="triggerConfirm(
                                        'Supprimer ce burger ?',
                                        '{{ addslashes($product->name) }} sera définitivement supprimé.',
                                        'delete',
                                        'Supprimer',
                                        'del-{{ $product->id }}')"
                                    title="Supprimer"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                           bg-red-50 text-red-600 hover:bg-red-100
                                           dark:bg-red-500/15 dark:text-red-400 dark:hover:bg-red-500/25
                                           transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Supprimer
                            </button>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <p class="text-5xl mb-3"></p>
                        <p class="font-semibold text-gray-400 dark:text-slate-500 mb-3">Aucun burger dans le catalogue</p>
                        <button onclick="openModal()" class="btn-primary text-xs">
                            Ajouter le premier burger
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="px-5 py-3 border-t border-gray-100 dark:border-slate-700">
            {{ $products->links() }}
        </div>
    @endif
</div>

</div>

{{-- ══════════════════════════════════
     MODAL PRODUIT
══════════════════════════════════ --}}
<div id="productModal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
         onclick="closeModal()"></div>
    <div class="relative w-full max-w-lg rounded-2xl shadow-2xl border overflow-hidden
                bg-white border-gray-100
                dark:bg-slate-800 dark:border-slate-700"
         style="animation: modalIn .25s ease">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <h2 class="font-bold text-gray-900 dark:text-white" id="modalTitle">Nouveau burger</h2>
            <button onclick="closeModal()"
                    class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-slate-700 transition text-lg">
                ✕
            </button>
        </div>

        <form id="productForm" method="POST"
              action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data"
              class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
            @csrf
            <div id="methodField"></div>

            <div>
                <label class="form-label">Nom *</label>
                <input type="text" name="name" id="m_name"
                       placeholder="Ex: ISI Classic" class="form-input" required>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Catégorie *</label>
                    <select name="category_id" id="m_category" class="form-input" required>
                        <option value="">Choisir...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Prix (FCFA) *</label>
                    <input type="number" name="price" id="m_price"
                           placeholder="2500" min="0" step="50"
                           class="form-input" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Stock *</label>
                    <input type="number" name="stock" id="m_stock"
                           placeholder="50" min="0" class="form-input" required>
                </div>
                <div class="flex items-end pb-2.5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_available" id="m_available"
                               value="1" checked
                               class="w-4 h-4 rounded text-blue-600">
                        <span class="text-sm font-medium text-gray-700 dark:text-slate-300">Visible catalogue</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="form-label">Description</label>
                <textarea name="description" id="m_description"
                          placeholder="Ingrédients, composition..." rows="2"
                          class="form-input resize-none"></textarea>
            </div>

            <div>
                <label class="form-label">Image</label>
                <img id="m_preview" src="" alt=""
                     class="w-16 h-16 object-cover rounded-xl mb-2 border border-gray-200 dark:border-slate-600 hidden">
                <input type="file" name="image" id="m_image"
                       accept="image/*"
                       onchange="previewImg(this)"
                       class="form-input text-xs">
            </div>

            <div class="flex gap-2 pt-1">
                <button type="button" onclick="closeModal()"
                        class="btn-secondary flex-1 justify-center">
                    Annuler
                </button>
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="m_submit">Créer le burger</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════
     MODAL CONFIRMATION
══════════════════════════════════ --}}
<div id="confirmModal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
         onclick="closeConfirm()"></div>
    <div class="relative w-full max-w-sm rounded-2xl shadow-2xl border p-6
                bg-white border-gray-100
                dark:bg-slate-800 dark:border-slate-700"
         style="animation: modalIn .2s ease">
        <div class="flex items-start gap-4 mb-5">
            <div id="confirmIcon"
                 class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 bg-red-100 dark:bg-red-500/20">
                🗑️
            </div>
            <div>
                <h3 id="confirmTitle" class="font-bold text-gray-900 dark:text-white"></h3>
                <p id="confirmMsg"  class="text-sm text-gray-500 dark:text-slate-400 mt-1"></p>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="closeConfirm()"
                    class="btn-secondary flex-1 justify-center">
                Annuler
            </button>
            <button id="confirmBtn" onclick="doConfirm()"
                    class="btn-danger flex-1 justify-center">
                Confirmer
            </button>
        </div>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity:0; transform:scale(.94) translateY(8px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
</style>

@endsection

@push('scripts')
<script>
const productsData = @json($products->items());
let confirmFormId  = null;

/* ── Modal Produit ── */
function openModal(product = null) {
    const modal = document.getElementById('productModal');
    const form  = document.getElementById('productForm');
    form.reset();
    document.getElementById('m_preview').classList.add('hidden');
    document.getElementById('methodField').innerHTML = '';

    if (product) {
        document.getElementById('modalTitle').textContent = 'Modifier : ' + product.name;
        document.getElementById('m_submit').textContent   = 'Enregistrer';
        form.action = `/admin/products/${product.id}`;
        document.getElementById('methodField').innerHTML =
            '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('m_name').value        = product.name || '';
        document.getElementById('m_category').value   = product.category_id || '';
        document.getElementById('m_price').value      = product.price || '';
        document.getElementById('m_stock').value      = product.stock || 0;
        document.getElementById('m_description').value = product.description || '';
        document.getElementById('m_available').checked = !!product.is_available;

        if (product.image) {
            const prev = document.getElementById('m_preview');
            prev.src = '/storage/' + product.image;
            prev.classList.remove('hidden');
        }
    } else {
        document.getElementById('modalTitle').textContent = 'Nouveau burger';
        document.getElementById('m_submit').textContent   = 'Créer le burger';
        form.action = '{{ route("admin.products.store") }}';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('productModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const prev   = document.getElementById('m_preview');
        reader.onload = e => { prev.src = e.target.result; prev.classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
    }
}

/* ── Modal Confirmation ── */
function triggerConfirm(title, msg, type, label, formId) {
    confirmFormId = formId;
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMsg').textContent   = msg;
    const icon = document.getElementById('confirmIcon');
    const btn  = document.getElementById('confirmBtn');

    if (type === 'delete') {
        icon.textContent  = '';
        icon.className    = 'w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 bg-red-100 dark:bg-red-500/20';
        btn.className     = 'btn-danger flex-1 justify-center';
    } else {
        icon.textContent  = '';
        icon.className    = 'w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 bg-amber-100 dark:bg-amber-500/20';
        btn.className     = 'btn-warning flex-1 justify-center';
    }
    btn.textContent = label;

    const modal = document.getElementById('confirmModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function doConfirm() {
    if (confirmFormId) document.getElementById(confirmFormId).submit();
    closeConfirm();
}

function closeConfirm() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModal(); closeConfirm(); }
});
</script>
@endpush