<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MailService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product', 'payment'])
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->when(request('search'), fn($q) =>
                $q->where('reference', 'like', '%'.request('search').'%')
                  ->orWhereHas('user', fn($u) =>
                      $u->where('name', 'like', '%'.request('search').'%')
                  )
            )
            ->when(request('date'), fn($q) => $q->whereDate('created_at', request('date')))
            ->latest()
            ->paginate(15);

        $counts = [
            'all'       => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready'     => Order::where('status', 'ready')->count(),
            'paid'      => Order::where('status', 'paid')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,paid,cancelled',
        ]);

        $newStatus = $request->status;

        if (!$order->canTransitionTo($newStatus)) {
            return back()->with('error',
                'Transition non autorisée : ' . $order->status_label . ' → ' . $newStatus
            );
        }

        $order->update(['status' => $newStatus]);

        // Email + PDF quand commande est PRÊTE
        if ($newStatus === Order::STATUS_READY) {
            $order->load(['items.product', 'user']);
            $sent = MailService::sendOrderReady($order);

            $msg = 'Commande ' . $order->reference . ' marquée comme prête.';
            $msg .= $sent
                ? ' 📧 Email + facture PDF envoyés au client.'
                : ' ⚠️ Commande mise à jour mais email non envoyé (voir logs).';

            return back()->with('success', $msg);
        }

        return back()->with('success',
            'Commande ' . $order->reference . ' → ' . $order->fresh()->status_label
        );
    }

    public function cancel(Order $order)
    {
        if (!$order->canTransitionTo(Order::STATUS_CANCELLED)) {
            return back()->with('error', 'Cette commande ne peut pas être annulée.');
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return back()->with('success', 'Commande ' . $order->reference . ' annulée.');
    }
}