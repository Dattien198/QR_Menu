<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        // Get all pending, confirmed, preparing orders for the kitchen screen
        $orders = Order::with(['items.menuItem', 'table'])
            ->whereIn('status', ['pending', 'confirmed', 'preparing'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('kitchen.index', compact('orders'));
    }

    public function updateItemStatus(Request $request, OrderItem $item)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,served'
        ]);

        $item->update(['status' => $validated['status']]);

        // Check if all items in the order are ready/served, if so, update the order status
        $order = $item->order;
        $allItemsDone = $order->items()->whereIn('status', ['pending', 'preparing'])->count() === 0;
        
        if ($allItemsDone) {
            $order->update(['status' => 'ready']);
            // TODO: Broadcast event that the order is ready for the waiter
        } else {
            // If at least one item is being prepared, set order to preparing
            if ($order->status === 'pending' || $order->status === 'confirmed') {
                $order->update(['status' => 'preparing']);
            }
        }

        return response()->json(['success' => true]);
    }
}
