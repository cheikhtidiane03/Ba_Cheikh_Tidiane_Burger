<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stats journalières ──────────────────────────
        $stats = [
            // Commandes en cours (pas payées, pas annulées)
            'orders_active'     => Order::today()->active()->count(),

            // Commandes validées (payées aujourd'hui)
            'orders_paid_today' => Order::today()
                                        ->where('status', Order::STATUS_PAID)
                                        ->count(),

            // Recettes journalières (total paiements reçus aujourd'hui)
            'revenue_today'     => Payment::whereDate('paid_at', today())->sum('amount'),

            // Ruptures de stock
            'out_of_stock'      => Product::outOfStock()->count(),
        ];

        // ── 5 dernières commandes ───────────────────────
        $recentOrders = Order::with('user')
                             ->latest()
                             ->take(5)
                             ->get();

        // ── Commandes par mois (12 derniers mois) ───────
        $ordersPerMonth = Order::select(
                DB::raw("TO_CHAR(created_at, 'Mon YYYY') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy(
                DB::raw("TO_CHAR(created_at, 'Mon YYYY')"),
                DB::raw("DATE_TRUNC('month', created_at)")
            )
            ->orderBy(DB::raw("DATE_TRUNC('month', created_at)"))
            ->get();

        // ── Produits par catégorie ───────────────────────
        $productsByCategory = Category::select(
                'categories.name',
                DB::raw('COUNT(products.id) as total')
            )
            ->leftJoin('products', function($join) {
                $join->on('categories.id', '=', 'products.category_id')
                     ->where('products.is_archived', false);
            })
            ->groupBy('categories.id', 'categories.name')
            ->having(DB::raw('COUNT(products.id)'), '>', 0)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentOrders',
            'ordersPerMonth',
            'productsByCategory'
        ));
    }
}