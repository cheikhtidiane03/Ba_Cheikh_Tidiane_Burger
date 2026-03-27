<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\CatalogController;
use Illuminate\Support\Facades\Route;

// ── Accueil ──────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->hasRole('gestionnaire')
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.catalog.index');
    }
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

// ── ESPACE GESTIONNAIRE ───────────────────────────
Route::prefix('admin')
     ->name('admin.')
     ->middleware(['auth', 'gestionnaire'])
     ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::patch('products/{product}/toggle-archive',
        [\App\Http\Controllers\Admin\ProductController::class, 'toggleArchive'])
        ->name('products.toggle-archive');

    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)
         ->only(['index', 'show']);
    Route::patch('orders/{order}/status',
        [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
        ->name('orders.update-status');
    Route::patch('orders/{order}/cancel',
        [\App\Http\Controllers\Admin\OrderController::class, 'cancel'])
        ->name('orders.cancel');

    Route::get('/payments',
        [\App\Http\Controllers\Admin\PaymentController::class, 'index'])
        ->name('payments.index');
    Route::post('/payments/{order}',
        [\App\Http\Controllers\Admin\PaymentController::class, 'store'])
        ->name('payments.store');
});

// ── ESPACE CLIENT ─────────────────────────────────
// Middleware : auth seulement (pas de restriction de rôle)
// Le gestionnaire ne peut PAS accéder ici (redirection à la connexion)
// mais le middleware IsClient n'est plus nécessaire
Route::prefix('client')
     ->name('client.')
     ->middleware(['auth'])
     ->group(function () {

    // Catalogue
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');

    // Commandes
    Route::get('/orders',
        [\App\Http\Controllers\Client\OrderController::class, 'index'])
        ->name('orders.index');
    Route::post('/orders',
        [\App\Http\Controllers\Client\OrderController::class, 'store'])
        ->name('orders.store');
    Route::get('/orders/{order}',
        [\App\Http\Controllers\Client\OrderController::class, 'show'])
        ->name('orders.show');
});