<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display the public QR menu for a table.
     */
    public function index($restaurantSlug, $qrToken, Request $request)
    {
        $restaurant = Restaurant::where('slug', $restaurantSlug)
            ->firstOrFail();

        $table = RestaurantTable::where('qr_token', $qrToken)
            ->whereHas('branch', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })
            ->with('branch') // eager-load to avoid N+1
            ->firstOrFail();

        // Generate a unique session token for this customer visit (4h cookie)
        if (!$request->session()->has('order_session_' . $table->id)) {
            $request->session()->put('order_session_' . $table->id, Str::random(40));
        }
        $sessionToken = $request->session()->get('order_session_' . $table->id);

        // Load categories with available menu items (eager-load addons too)
        $categories = Category::where('restaurant_id', $restaurant->id)
            ->whereNull('parent_id')   // only top-level
            ->with(['menuItems' => function ($query) {
                $query->where('status', '!=', 'upcoming')
                      ->with('addons')
                      ->orderBy('is_featured', 'desc');
            }])
            ->orderBy('order_index')
            ->get()
            ->filter(fn($cat) => $cat->menuItems->count() > 0) // hide empty categories
            ->values();

        // Load active orders for this session so customer can track status
        $sessionOrders = Order::where('session_token', $sessionToken)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['items.menuItem:id,name,images'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($o) {
                return [
                    'id'           => $o->id,
                    'order_code'   => $o->order_code,
                    'status'       => $o->status,
                    'status_label' => match ($o->status) {
                        'pending'   => 'Đang chờ xác nhận',
                        'confirmed' => 'Đã xác nhận',
                        'preparing' => 'Đang chuẩn bị',
                        'ready'     => 'Sẵn sàng phục vụ',
                        'served'    => 'Đã phục vụ',
                        default     => $o->status,
                    },
                    'total'      => (float) $o->total,
                    'created_at' => $o->created_at->format('H:i'),
                    'items'      => $o->items->map(fn($i) => [
                        'name'     => $i->menuItem->name ?? 'Món đã xóa',
                        'quantity' => $i->quantity,
                        'status'   => $i->status,
                        'note'     => $i->note,
                    ]),
                ];
            });

        $allTables = RestaurantTable::where('branch_id', $table->branch_id)->orderBy('name')->get();

        return view('menu.index', compact('restaurant', 'table', 'allTables', 'categories', 'sessionToken', 'sessionOrders'));
    }

    /**
     * Place an order from the QR menu.
     */
    public function storeOrder($restaurantSlug, $qrToken, Request $request)
    {
        $restaurant = Restaurant::where('slug', $restaurantSlug)->firstOrFail();
        $table = RestaurantTable::where('qr_token', $qrToken)
            ->whereHas('branch', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })
            ->with('branch')
            ->firstOrFail();

        $validated = $request->validate([
            'table_id'          => 'nullable|exists:restaurant_tables,id',
            'cart'              => 'required|array|min:1',
            'cart.*.id'         => 'required|exists:menu_items,id',
            'cart.*.qty'        => 'required|integer|min:1|max:50',
            'cart.*.price'      => 'required|numeric|min:0',
            'cart.*.note'       => 'nullable|string|max:255',
            'customer_name'     => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'note'              => 'nullable|string|max:1000',
        ]);

        // Override table if user selected a different table in the checkout modal
        if (!empty($validated['table_id']) && $validated['table_id'] != $table->id) {
            $table = RestaurantTable::where('id', $validated['table_id'])
                ->where('branch_id', $table->branch_id)
                ->firstOrFail();
        }

        // Get or create session token
        $sessionToken = $request->session()->get(
            'order_session_' . $table->id,
            Str::random(40)
        );
        $request->session()->put('order_session_' . $table->id, $sessionToken);

        DB::beginTransaction();
        try {
            // Verify prices server-side
            $itemIds  = collect($validated['cart'])->pluck('id');
            $dbItems  = MenuItem::whereIn('id', $itemIds)->get()->keyBy('id');

            $subtotal = collect($validated['cart'])->sum(function ($cartItem) use ($dbItems) {
                $db = $dbItems[$cartItem['id']] ?? null;
                return ($db ? $db->price : $cartItem['price']) * $cartItem['qty'];
            });

            $taxRate = $restaurant->vat ?? 0;
            $tax     = round($subtotal * ($taxRate / 100), 2);
            $total   = $subtotal + $tax;

            // Create the order
            $order = Order::create([
                'order_code'          => 'ORD-' . strtoupper(Str::random(6)),
                'restaurant_table_id' => $table->id,
                'user_id'             => auth()->id(),
                'status'              => 'pending',
                'customer_name'       => $validated['customer_name'] ?? null,
                'phone'               => $validated['phone'] ?? null,
                'note'                => $validated['note'] ?? null,
                'subtotal'            => $subtotal,
                'discount'            => 0,
                'tax'                 => $tax,
                'total'               => $total,
                'payment_status'      => 'unpaid',
                'session_token'       => $sessionToken,
            ]);

            // Mark table as occupied
            if ($table->status === 'empty') {
                $table->update(['status' => 'occupied']);
            }

            // Create order items
            foreach ($validated['cart'] as $cartItem) {
                $db = $dbItems[$cartItem['id']] ?? null;
                OrderItem::create([
                    'order_id'     => $order->id,
                    'menu_item_id' => $cartItem['id'],
                    'quantity'     => $cartItem['qty'],
                    'price'        => $db ? $db->price : $cartItem['price'],
                    'note'         => $cartItem['note'] ?? null,
                    'status'       => 'pending',
                ]);
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'order_code'   => $order->order_code,
                'order_id'     => $order->id,
                'total'        => $order->total,
                'message'      => 'Đơn hàng đã được gửi đến nhà bếp!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get session orders status (for polling).
     */
    public function sessionOrders($restaurantSlug, $qrToken, Request $request)
    {
        $restaurant = Restaurant::where('slug', $restaurantSlug)->firstOrFail();
        $table = RestaurantTable::where('qr_token', $qrToken)
            ->whereHas('branch', function ($q) use ($restaurant) {
                $q->where('restaurant_id', $restaurant->id);
            })
            ->firstOrFail();

        $sessionToken = $request->session()->get('order_session_' . $table->id);

        if (!$sessionToken) {
            return response()->json(['orders' => []]);
        }

        $orders = Order::where('session_token', $sessionToken)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['items.menuItem:id,name'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($order) {
                return [
                    'id'             => $order->id,
                    'order_code'     => $order->order_code,
                    'status'         => $order->status,
                    'status_label'   => $this->statusLabel($order->status),
                    'payment_status' => $order->payment_status,
                    'total'          => $order->total,
                    'items'          => $order->items->map(fn($i) => [
                        'name'     => $i->menuItem->name ?? 'Món đã xóa',
                        'quantity' => $i->quantity,
                        'status'   => $i->status,
                        'note'     => $i->note,
                    ]),
                    'created_at' => $order->created_at->format('H:i'),
                ];
            });

        return response()->json(['orders' => $orders]);
    }

    /**
     * Get available empty tables for table-switching feature.
     */
    public function getTables($restaurantSlug)
    {
        $restaurant = Restaurant::where('slug', $restaurantSlug)->firstOrFail();

        $branches = $restaurant->branches()->with(['tables' => function ($query) {
            $query->where('status', 'empty');
        }])->get();

        return response()->json([
            'success'  => true,
            'branches' => $branches,
        ]);
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'pending'    => 'Đang chờ xác nhận',
            'confirmed'  => 'Đã xác nhận',
            'preparing'  => 'Đang chuẩn bị',
            'ready'      => 'Sẵn sàng phục vụ',
            'served'     => 'Đã phục vụ',
            'completed'  => 'Hoàn thành',
            'cancelled'  => 'Đã hủy',
            default      => $status,
        };
    }
}
