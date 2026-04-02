<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                       ->with(['items.product', 'payment'])
                       ->latest()
                       ->paginate(10);

        return view('client.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        $order->load(['items.product', 'payment']);
        return view('client.orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1|max:50',
            'notes'              => 'nullable|string|max:500',
        ]);

        // Vérifications stock
        $items  = collect($request->items);
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

        $orderId = null;

        DB::transaction(function () use ($request, $items, &$orderId) {

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

                $product->decrement('stock', $item['quantity']);
                $total += $product->price * $item['quantity'];
            }

            $order->update(['total_amount' => $total]);
            $order->load(['items.product', 'user']);

            // Email confirmation → client
            MailService::sendOrderConfirmed($order);

            // Email notification → gestionnaires
            MailService::sendNewOrderToAdmins($order);

            $orderId = $order->id;
        });

        return redirect()
            ->route('client.orders.show', $orderId)
            ->with('success', '🎉 Commande passée avec succès ! Un email de confirmation vous a été envoyé.');
    }
}