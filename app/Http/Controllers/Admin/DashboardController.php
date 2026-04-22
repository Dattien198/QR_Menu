<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // For a true multi-tenant we should scope by auth()->user()->restaurant_id
        // But for demo purposes, let's grab global or just general scoped stats.
        $restaurantId = null; 
        if (auth()->check() && auth()->user()->restaurant_id) {
            $restaurantId = auth()->user()->restaurant_id;
        }

        $today = Carbon::today();

        // Query Base
        $ordersQuery = Order::query();
        if ($restaurantId) {
            $ordersQuery->whereHas('table.branch', function($q) use ($restaurantId) {
                $q->where('restaurant_id', $restaurantId);
            });
        }

        // Stats - Today
        $completedOrdersQuery = (clone $ordersQuery)->where(function($query) {
            $query->where('status', 'completed')->orWhere('payment_status', 'paid');
        });

        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        $totalOrdersToday = (clone $completedOrdersQuery)->whereBetween('updated_at', [$startOfDay, $endOfDay])->count();
        $totalRevenueToday = (clone $completedOrdersQuery)->whereBetween('updated_at', [$startOfDay, $endOfDay])->sum('total');
            
        // Stats - This Month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $totalOrdersMonth = (clone $completedOrdersQuery)->whereBetween('updated_at', [$startOfMonth, $endOfMonth])->count();
        $totalRevenueMonth = (clone $completedOrdersQuery)->whereBetween('updated_at', [$startOfMonth, $endOfMonth])->sum('total');
        
        $tableQuery = RestaurantTable::query();
        if ($restaurantId) {
            $tableQuery->whereHas('branch', function($q) use ($restaurantId) {
                $q->where('restaurant_id', $restaurantId);
            });
        }
        $activeTables = $tableQuery->where('status', 'occupied')->count();

        $itemQuery = \App\Models\OrderItem::query();
        if ($restaurantId) {
            $itemQuery->whereHas('order.table.branch', function($q) use ($restaurantId) {
                $q->where('restaurant_id', $restaurantId);
            });
        }
        // Advanced Stats
        $pendingItems = (clone $itemQuery)->whereIn('status', ['pending', 'preparing'])->count();
        $readyItems = (clone $itemQuery)->where('status', 'ready')->count();
        $waitingItems = (clone $itemQuery)->where('status', 'pending')->count();

        // Recent Orders
        $recentOrders = (clone $ordersQuery)->with('table')->latest()->take(5)->get();

        // Recent Activity Feed
        $recentActivities = \App\Models\OrderItem::with(['order.table', 'menuItem'])
            ->whereIn('status', ['preparing', 'ready', 'served'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalOrdersToday',
            'totalRevenueToday',
            'totalOrdersMonth',
            'totalRevenueMonth',
            'activeTables',
            'pendingItems',
            'readyItems',
            'waitingItems',
            'recentOrders',
            'recentActivities'
        ));
    }
}
