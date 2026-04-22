@extends('layouts.admin')

@section('header')
    <div class="flex flex-col md:flex-row md:items-center justify-between text-left">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Tổng quan hệ thống</h2>
            <p class="text-sm text-slate-500">Các chỉ số thời gian thực và hiệu suất hôm nay.</p>
        </div>
        <div class="mt-4 md:mt-0 text-sm font-medium text-slate-500">
            {{ \Carbon\Carbon::now()->format('l, d/m/Y') }}
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6" x-data="dashboardState()" x-init="init()">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue -->
        <div class="bg-gradient-to-br from-orange-500 to-amber-500 rounded-3xl p-6 shadow-xl shadow-orange-500/20 flex flex-col hover:-translate-y-1.5 transition-all duration-300 relative overflow-hidden group border border-orange-400">
            <div class="absolute right-[-10%] top-[-10%] w-32 h-32 bg-white/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
            <div class="flex justify-between items-start mb-6 relative z-10">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-white backdrop-blur-md border border-white/20 shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-white/20 text-white uppercase tracking-widest backdrop-blur-sm border border-white/20">
                    Doanh thu
                </span>
            </div>
            <div class="relative z-10">
                <h3 class="text-4xl font-black text-white">{{ number_format($totalRevenueMonth, 0) }}<span class="text-xl font-bold ml-1 opacity-80">đ</span></h3>
                <p class="text-sm text-orange-100 font-medium mt-2">Tổng Tháng này ({{ $totalOrdersMonth }} đơn)</p>
            </div>
        </div>

        <!-- Waiting Items -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col hover:-translate-y-1.5 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-500 border border-yellow-100">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-yellow-100 text-yellow-700 uppercase tracking-widest">
                    Đang đợi: {{ $waitingItems }}
                </span>
            </div>
            <div>
                <h3 class="text-4xl font-black text-slate-800">{{ $pendingItems }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-2">Món đang chế biến</p>
            </div>
        </div>

        <!-- Ready Items -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col hover:-translate-y-1.5 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 border border-emerald-100">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <template x-if="true">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 animate-pulse uppercase tracking-widest">
                        Mới xong!
                    </span>
                </template>
            </div>
            <div>
                <h3 class="text-4xl font-black text-emerald-500">{{ $readyItems }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-2">Món chờ phục vụ</p>
            </div>
        </div>

        <!-- Active Tables -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col hover:-translate-y-1.5 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-500 border border-indigo-100">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-4xl font-black text-slate-800">{{ $activeTables }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-2">Bàn đang sử dụng</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activities Feed -->
        <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col min-h-[400px]">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Hoạt động Bếp</h3>
                <span class="flex h-2 w-2 rounded-full bg-green-500 animate-ping"></span>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-4 max-h-[500px] custom-scrollbar">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start gap-3 p-3 rounded-xl border border-slate-50 {{ $activity->status === 'ready' ? 'bg-green-50/50' : 'bg-slate-50/30' }}">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 
                            {{ $activity->status === 'ready' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }}">
                            <span class="text-xs font-bold">{{ $activity->order->table->name ?? '?' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $activity->menuItem->name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $activity->status === 'ready' ? 'Đã hoàn thành' : 'Đang chế biến' }}
                                <span class="mx-1">•</span>
                                {{ $activity->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-slate-400 py-20">
                        <svg class="w-12 h-12 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm">Chưa có hoạt động nào</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden h-full">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Đơn hàng mới nhất</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-orange-600 hover:text-orange-700">Xem tất cả &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Table</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">#{{ $order->order_code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-700">{{ $order->table->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'ready') bg-teal-100 text-teal-800
                                        @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                                        @else bg-blue-100 text-blue-800 @endif
                                    ">
                                        {{ $order->status === 'pending' ? 'Chờ xử lý' : 
                                          ($order->status === 'completed' ? 'Hoàn thành' : 
                                          ($order->status === 'ready' ? 'Xong, có thể phục vụ' : 
                                          ($order->status === 'preparing' ? 'Đang nấu' : 
                                          ($order->status === 'served' ? 'Đã lên món' : $order->status)))) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900 text-right">{{ number_format($order->total, 0) }}đ</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">No recent orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function dashboardState() {
        return {
            init() {
                // Tự động tải lại trang sau mỗi 10 giây để cập nhật số liệu mới nhất
                setInterval(() => {
                    window.location.reload();
                }, 10000);
            }
        }
    }
</script>
@endsection
