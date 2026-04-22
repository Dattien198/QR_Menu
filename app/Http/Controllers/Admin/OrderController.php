<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['table', 'items'])->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['table', 'items.menuItem', 'payments']);
        return view('admin.orders.show', compact('order'));
    }
    public function edit(Order $order)
    {
        $order->load(['table']);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,served,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'note' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Cập nhật đơn hàng thành công.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng.');
    }
}
