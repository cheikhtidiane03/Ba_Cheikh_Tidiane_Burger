<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['order.user', 'recorder'])
                           ->latest('paid_at')
                           ->paginate(15);

        $totalToday = Payment::whereDate('paid_at', today())->sum('amount');
        $totalMonth = Payment::whereMonth('paid_at', now()->month)->sum('amount');

        return view('admin.payments.index', compact('payments', 'totalToday', 'totalMonth'));
    }

    public function store(Request $request, Order $order)
    {
        // Vérifications
        if (!$order->isReady()) {
            return back()->with('error', 'La commande doit être "Prête" avant d\'enregistrer le paiement.');
        }

        if ($order->payment) {
            return back()->with('error', 'Cette commande a déjà été payée.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'notes'  => 'nullable|string|max:500',
        ]);

        Payment::create([
            'order_id'    => $order->id,
            'recorded_by' => auth()->id(),
            'amount'      => $request->amount,
            'method'      => 'cash',
            'paid_at'     => now(),
            'notes'       => $request->notes,
        ]);

        // Passer la commande à "payée"
        $order->update(['status' => Order::STATUS_PAID]);

        return redirect()->route('admin.orders.show', $order)
                         ->with('success', 'Paiement de ' . number_format($request->amount, 0, ',', ' ') . ' FCFA enregistré !');
    }
}