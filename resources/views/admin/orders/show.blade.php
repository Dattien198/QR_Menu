@extends('layouts.admin')

@section('header')
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.orders.index') }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-orange-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Đơn hàng #{{ $order->order_code }}</h2>
                <p class="text-sm text-slate-500">Đặt lúc {{ $order->created_at->format('H:i • d/m/Y') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold uppercase tracking-wider">
                {{ $order->status === 'pending' ? 'Chờ duyệt' : ($order->status === 'preparing' ? 'Đang chế biến' : ($order->status === 'ready' ? 'Sẵn sàng' : ($order->status === 'served' ? 'Đã phục vụ' : ($order->status === 'completed' ? 'Hoàn thành' : $order->status)))) }}
            </span>
            <span class="px-3 py-1 {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full text-xs font-bold uppercase tracking-wider">
                {{ $order->payment_status === 'unpaid' ? 'Chưa thanh toán' : ($order->payment_status === 'partial' ? 'Đã trả một phần' : 'Đã thanh toán') }}
            </span>
        </div>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-left">
    <!-- Order Items -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-900 text-sm">Chi tiết món ăn</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($order->items as $item)
                    <div class="p-6 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden">
                                @if($item->menuItem && $item->menuItem->image)
                                    <img src="{{ Storage::url($item->menuItem->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">{{ $item->menuItem->name ?? 'Món đã xóa' }}</h4>
                                <p class="text-sm text-slate-500">SL: {{ $item->quantity }} • Đơn giá: {{ number_format($item->price, 0) }}đ</p>
                                @if($item->notes)
                                    <p class="mt-1 text-xs text-orange-600 font-medium">Ghi chú: {{ $item->notes }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-slate-900">{{ number_format($item->subtotal, 0) }}đ</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 space-y-2">
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Tạm tính</span>
                    <span>{{ number_format($order->subtotal, 0) }}đ</span>
                </div>
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Giảm giá</span>
                    <span>-{{ number_format($order->discount, 0) }}đ</span>
                </div>
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Thuế (VAT)</span>
                    <span>{{ number_format($order->tax, 0) }}đ</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-200 text-lg font-bold text-slate-900">
                    <span>Tổng cộng</span>
                    <span class="text-orange-600">{{ number_format($order->total, 0) }}đ</span>
                </div>
            </div>
        </div>

        @if($order->note)
            <div class="bg-orange-50 border border-orange-100 rounded-2xl p-6">
                <h4 class="text-xs font-bold text-orange-800 uppercase tracking-wider mb-2">Ghi chú từ khách hàng</h4>
                <p class="text-slate-700 italic">"{{ $order->note }}"</p>
            </div>
        @endif
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <!-- Table Info -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 mb-4 pb-4 border-b border-slate-100 text-sm italic">Thông tin bàn</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Bàn</span>
                    <span class="text-sm font-bold text-slate-900">{{ $order->table->name ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Chi nhánh</span>
                    <span class="text-sm font-bold text-slate-900">{{ $order->table->branch->name ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Khách hàng</span>
                    <span class="text-sm font-bold text-slate-900">{{ $order->customer_name ?? 'Khách lẻ' }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 mb-4 pb-4 border-b border-slate-100 text-sm italic">Lịch sử thanh toán</h3>
            <div class="space-y-3">
                @forelse($order->payments as $payment)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <div>
                            <p class="text-xs font-bold text-slate-900 uppercase tracking-tighter">{{ $payment->payment_method }}</p>
                            <p class="text-[10px] text-slate-500">{{ $payment->created_at->format('H:i') }}</p>
                        </div>
                        <span class="text-sm font-bold text-green-600">+{{ number_format($payment->amount, 0) }}đ</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 italic text-center py-2">Chưa có dữ liệu thanh toán.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
