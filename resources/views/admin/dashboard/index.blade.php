@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de la journée')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">

    {{-- Commandes du jour --}}
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Commandes aujourd'hui</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['orders_today'] }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Commandes en cours --}}
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">En cours</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['orders_active'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Recettes du jour --}}
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Recettes du jour</p>
                <p class="text-3xl font-bold text-green-600 mt-1">
                    {{ number_format($stats['revenue_today'], 0, ',', ' ') }}
                    <span class="text-sm font-normal">FCFA</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Produits en rupture --}}
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Ruptures de stock</p>
                <p class="text-3xl font-bold {{ $stats['out_of_stock'] > 0 ? 'text-red-600' : 'text-gray-800' }} mt-1">
                    {{ $stats['out_of_stock'] }}
                </p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- Message de bienvenue si pas encore de données --}}
<div class="card mt-6">
    <div class="text-center py-8">
        <span class="text-6xl">🍔</span>
        <h2 class="text-xl font-semibold text-gray-700 mt-4">Bienvenue dans l'espace gestionnaire !</h2>
        <p class="text-gray-400 mt-2">Les statistiques et commandes apparaîtront ici au fur et à mesure.</p>
        <div class="flex justify-center gap-4 mt-6">
            <a href="{{ route('admin.products.index') }}" class="btn-primary">
                Gérer les produits
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn-secondary">
                Voir les commandes
            </a>
        </div>
    </div>
</div>
@endsection