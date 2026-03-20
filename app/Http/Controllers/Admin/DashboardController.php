<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'orders_today'   => Order::today()->count(),
            'orders_active'  => Order::today()->active()->count(),
            'revenue_today'  => Payment::whereDate('paid_at', today())->sum('amount'),
            'products_total' => Product::notArchived()->count(),
            'out_of_stock'   => Product::outOfStock()->count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}