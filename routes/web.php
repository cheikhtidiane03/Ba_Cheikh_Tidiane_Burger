<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\CatalogController;
use Illuminate\Support\Facades\Route;

// -----------------------------------------------
// Page d'accueil → redirige selon connexion
// -----------------------------------------------
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('gestionnaire')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('client.catalog.index');
    }
    return redirect()->route('login');
});

// -----------------------------------------------
// Routes Breeze (login, register, logout...)
// -----------------------------------------------
require __DIR__.'/auth.php';

// -----------------------------------------------
// ESPACE GESTIONNAIRE
// Protégé par : auth + middleware gestionnaire
// -----------------------------------------------
Route::prefix('admin')
     ->name('admin.')
     ->middleware(['auth', 'gestionnaire'])
     ->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    // Produits (étape 4)
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::patch('products/{product}/toggle-archive', [\App\Http\Controllers\Admin\ProductController::class, 'toggleArchive'])
         ->name('products.toggle-archive');

    // Commandes (étape 5)
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)
         ->only(['index', 'show']);
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
         ->name('orders.update-status');
    Route::patch('orders/{order}/cancel', [\App\Http\Controllers\Admin\OrderController::class, 'cancel'])
         ->name('orders.cancel');

    // Paiements (étape 6)
    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])
         ->name('payments.index');
    Route::post('/payments/{order}', [\App\Http\Controllers\Admin\PaymentController::class, 'store'])
         ->name('payments.store');
});

// -----------------------------------------------
// ESPACE CLIENT
// Protégé par : auth + middleware client
// -----------------------------------------------
Route::prefix('client')
     ->name('client.')
     ->middleware(['auth', 'client'])
     ->group(function () {

    // Catalogue
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');

    // Commandes (étape 5)
    Route::get('/orders', [\App\Http\Controllers\Client\OrderController::class, 'index'])
         ->name('orders.index');
    Route::post('/orders', [\App\Http\Controllers\Client\OrderController::class, 'store'])
         ->name('orders.store');
    Route::get('/orders/{order}', [\App\Http\Controllers\Client\OrderController::class, 'show'])
         ->name('orders.show');
});