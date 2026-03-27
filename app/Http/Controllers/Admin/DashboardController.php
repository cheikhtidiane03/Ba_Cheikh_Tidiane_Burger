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
        // ══════════════════════════════════════
        // 1. STATS JOURNALIÈRES
        // ══════════════════════════════════════

        // Commandes en cours de la journée (pas payées, pas annulées)
        $ordersActive = Order::today()->active()->count();

        // Commandes validées (payées aujourd'hui)
        $ordersPaidToday = Order::today()
                                ->where('status', Order::STATUS_PAID)
                                ->count();

        // Recettes journalières = total des paiements reçus aujourd'hui
        $revenueToday = Payment::whereDate('paid_at', today())->sum('amount');

        // Ruptures de stock
        $outOfStock = Product::outOfStock()->count();

        $stats = [
            'orders_active'     => $ordersActive,
            'orders_paid_today' => $ordersPaidToday,
            'revenue_today'     => $revenueToday,
            'out_of_stock'      => $outOfStock,
            'orders_today'      => Order::today()->count(),
        ];

        // ══════════════════════════════════════
        // 2. GRAPHIQUE : Commandes par mois (12 derniers mois)
        // ══════════════════════════════════════
        $ordersPerMonth = Order::select(
                DB::raw("TO_CHAR(created_at, 'Mon') AS month"),
                DB::raw("TO_CHAR(created_at, 'MM') AS month_num"),
                DB::raw("DATE_TRUNC('month', created_at) AS month_date"),
                DB::raw('COUNT(*) AS total')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy(
                DB::raw("TO_CHAR(created_at, 'Mon')"),
                DB::raw("TO_CHAR(created_at, 'MM')"),
                DB::raw("DATE_TRUNC('month', created_at)")
            )
            ->orderBy('month_date')
            ->get();

        // ══════════════════════════════════════
        // 3. GRAPHIQUE : Nombre de produits par catégorie
        // ══════════════════════════════════════
        $productsByCategory = Category::select(
                'categories.name',
                DB::raw('COUNT(products.id) AS total')
            )
            ->leftJoin('products', function ($join) {
                $join->on('categories.id', '=', 'products.category_id')
                     ->where('products.is_archived', false);
            })
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc(DB::raw('COUNT(products.id)'))
            ->get();

        // ══════════════════════════════════════
        // 4. DERNIÈRES COMMANDES
        // ══════════════════════════════════════
        $recentOrders = Order::with('user')
                             ->latest()
                             ->take(6)
                             ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'ordersPerMonth',
            'productsByCategory',
            'recentOrders'
        ));
    }
}