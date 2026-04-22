<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index()
    {
        // Show orders that are active (not fully paid or recently finished)
        $orders = Order::with(['items', 'table', 'payments'])
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'served'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cashier.index', compact('orders'));
    }

    public function processPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,credit_card,mobile_wallet,bank_transfer',
        ]);

        DB::beginTransaction();
        try {
            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'status' => 'completed',
                'transaction_id' => 'TXN' . strtoupper(uniqid()),
            ]);

            // Calculate paid amount
            $paidTotal = $order->payments()->sum('amount') + $validated['amount'];
            
            // Update order status
            if ($paidTotal >= $order->total) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'completed' // Or keep it served depending on business logic
                ]);
                
                // Free the table
                $order->table->update(['status' => 'empty']);
            } else {
                $order->update(['payment_status' => 'partial']);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Payment processed successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
}
