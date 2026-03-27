<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmedMail;
use App\Mail\NewOrderAdminMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Liste des commandes du client connecté
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                       ->with(['items.product', 'payment'])
                       ->latest()
                       ->paginate(10);

        return view('client.orders.index', compact('orders'));
    }

    /**
     * Détail d'une commande du client
     */
    public function show(Order $order)
    {
        // Vérifier que la commande appartient au client connecté
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load(['items.product', 'payment']);

        return view('client.orders.show', compact('order'));
    }

    /**
     * Passer une commande
     */
    public function store(Request $request)
    {
        $request->validate([
            'items'                    => 'required|array|min:1',
            'items.*.product_id'       => 'required|exists:products,id',
            'items.*.quantity'         => 'required|integer|min:1|max:50',
            'notes'                    => 'nullable|string|max:500',
        ]);

        // Vérifier disponibilité + stock de chaque produit
        $items = collect($request->items);
        $errors = [];

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            if (!$product || !$product->isOrderable()) {
                $errors[] = ($product->name ?? 'Produit') . ' n\'est plus disponible.';
                continue;
            }

            if ($product->stock < $item['quantity']) {
                $errors[] = $product->name . ' : stock insuffisant (disponible : ' . $product->stock . ').';
            }
        }

        if (!empty($errors)) {
            return back()->with('error', implode(' | ', $errors));
        }

        // Créer la commande en transaction
        DB::transaction(function () use ($request, $items) {

            $order = Order::create([
                'user_id'      => auth()->id(),
                'status'       => Order::STATUS_PENDING,
                'total_amount' => 0,
                'notes'        => $request->notes,
            ]);

            $total = 0;

            foreach ($items as $item) {
                $product = Product::find($item['product_id']);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal'   => $product->price * $item['quantity'],
                ]);

                // Décrémenter le stock
                $product->decrement('stock', $item['quantity']);

                $total += $product->price * $item['quantity'];
            }

            // Mettre à jour le total
            $order->update(['total_amount' => $total]);

            // Email confirmation client
            try {
                Mail::to(auth()->user()->email)
                    ->send(new OrderConfirmedMail($order->load('items.product')));
            } catch (\Exception $e) {
                \Log::error('Email confirmation client : ' . $e->getMessage());
            }

            // Notification gestionnaire
            try {
                $admins = \App\Models\User::role('gestionnaire')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new NewOrderAdminMail($order));
                }
            } catch (\Exception $e) {
                \Log::error('Email notification admin : ' . $e->getMessage());
            }

            session(['last_order_id' => $order->id]);
        });

        $orderId = session('last_order_id');

        return redirect()->route('client.orders.show', $orderId)
                         ->with('success', '🎉 Commande passée avec succès ! Un email de confirmation vous a été envoyé.');
    }
}