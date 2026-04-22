@extends('layouts.admin')

@section('header', 'Màn hình Thu ngân')

@section('content')
<div class="space-y-6" x-data="cashierState()">

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Đơn hàng đang hoạt động</h2>
            <p class="text-sm text-slate-500">Quản lý thanh toán và các bàn đang mở.</p>
        </div>
        <div class="flex space-x-2">
            <!-- Filter buttons can go here -->
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl shadow-sm text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl shadow-sm text-sm font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($orders as $order)
            <div class="bg-white border text-left border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col transition-all hover:shadow-md">
                <!-- Status Bar -->
                <div class="px-5 py-3 border-b flex items-center justify-between
                    @if($order->status === 'served') bg-green-50 border-green-100 text-green-800
                    @elseif($order->status === 'ready') bg-yellow-50 border-yellow-100 text-yellow-800
                    @elseif($order->status === 'preparing') bg-orange-50 border-orange-100 text-orange-800
                    @else bg-slate-50 border-slate-100 text-slate-800 @endif
                ">
                    <span class="font-bold text-sm tracking-wider uppercase">
                        {{ $order->table->name }}
                    </span>
                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-md tracking-wider
                        @if($order->status === 'served') bg-green-200 text-green-900
                        @elseif($order->status === 'ready') bg-yellow-200 text-yellow-900
                        @elseif($order->status === 'preparing') bg-orange-200 text-orange-900
                        @else bg-slate-200 text-slate-900 @endif
                    ">{{ $order->status === 'pending' ? 'Chờ duyệt' : ($order->status === 'preparing' ? 'Đang chế biến' : ($order->status === 'ready' ? 'Sẵn sàng' : ($order->status === 'served' ? 'Đã phục vụ' : $order->status))) }}</span>
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="text-xs text-slate-400 font-medium">Order #{{ $order->order_code }}</div>
                            <div class="text-xs text-slate-400 mt-1">{{ $order->created_at->format('H:i') }}</div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold
                                {{ $order->payment_status === 'partial' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Items Summary -->
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-700 mb-2">{{ $order->items->count() }} Món</p>
                        <div class="space-y-1 mb-4 h-24 overflow-y-auto pr-1 custom-scrollbar">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600"><span class="font-bold mr-1">{{ $item->quantity }}x</span> {{ $item->menuItem->name }}</span>
                                    <span class="text-slate-500 font-medium">{{ number_format($item->price * $item->quantity, 0) }}đ</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="border-t border-slate-100 pt-4 mt-auto">
                        @php
                            $paid = $order->payments->sum('amount');
                            $balance = max(0, $order->total - $paid);
                        @endphp
                        
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-500">Tổng cộng</span>
                            <span class="font-bold text-slate-900">{{ number_format($order->total, 0) }}đ</span>
                        </div>
                        
                        @if($paid > 0)
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-green-600">Đã trả</span>
                            <span class="font-bold text-green-600">-{{ number_format($paid, 0) }}đ</span>
                        </div>
                        <div class="flex justify-between text-base mt-2 pt-2 border-t border-slate-100">
                            <span class="font-bold text-red-500">Còn lại</span>
                            <span class="font-bold text-red-500">{{ number_format($balance, 0) }}đ</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                    <button class="flex-1 p-2 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors shadow-sm text-left">
                        In hóa đơn
                    </button>
                    <button @click="openPaymentModal({{ $order->id }}, {{ $balance > 0 ? $balance : $order->total }}, '{{ $order->order_code }}')" class="flex-1 p-2 bg-orange-600 text-white rounded-xl font-bold text-sm hover:bg-orange-700 transition-colors shadow-sm shadow-orange-200">
                        Thanh toán
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 py-16 text-center bg-white rounded-2xl border border-slate-200">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-sm font-medium text-slate-900">No active orders</h3>
                <p class="text-xs text-slate-500 mt-1">Waiting for the next customer.</p>
            </div>
        @endforelse
    </div>

    <!-- Payment Modal -->
    <template x-teleport="body">
        <div x-show="paymentModalOpen" x-cloak class="relative z-50">
            <!-- Backdrop -->
            <div x-show="paymentModalOpen" x-transition.opacity.duration.300ms class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closePaymentModal()"></div>
            
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div x-show="paymentModalOpen" 
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all" @click.stop>
                        
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                            <h3 class="text-lg font-bold text-slate-900">Thanh toán <span class="text-sm font-medium text-slate-500 ml-2" x-text="'#' + currentOrderCode"></span></h3>
                            <button @click="closePaymentModal()" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <form :action="'/admin/cashier/order/' + currentOrderId + '/pay'" method="POST" class="p-6 space-y-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Amount to Pay (Balance: <span x-text="balance"></span>)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-bold">đ</span>
                                    <input type="number" name="amount" x-model="amount" class="w-full pl-8 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 font-bold text-xl text-slate-900" required step="1" min="0">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Payment Method</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer border rounded-xl p-3 flex flex-col items-center justify-center gap-2 hover:bg-slate-50 transition-colors"
                                           :class="method === 'cash' ? 'border-orange-500 bg-orange-50 text-orange-700' : 'border-slate-200 text-slate-600'"
                                           @click="method = 'cash'">
                                        <input type="radio" name="method" value="cash" class="sr-only" x-model="method">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <span class="text-xs font-bold">Tiền mặt</span>
                                    </label>
                                    
                                    <label class="cursor-pointer border rounded-xl p-3 flex flex-col items-center justify-center gap-2 hover:bg-slate-50 transition-colors"
                                           :class="method === 'credit_card' ? 'border-orange-500 bg-orange-50 text-orange-700' : 'border-slate-200 text-slate-600'"
                                           @click="method = 'credit_card'">
                                        <input type="radio" name="method" value="credit_card" class="sr-only" x-model="method">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        <span class="text-xs font-bold">Thẻ tín dụng</span>
                                    </label>
                                    
                                    <label class="cursor-pointer border rounded-xl p-3 flex flex-col items-center justify-center gap-2 hover:bg-slate-50 transition-colors"
                                           :class="method === 'mobile_wallet' ? 'border-orange-500 bg-orange-50 text-orange-700' : 'border-slate-200 text-slate-600'"
                                           @click="method = 'mobile_wallet'">
                                        <input type="radio" name="method" value="mobile_wallet" class="sr-only" x-model="method">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-bold">Ví điện tử</span>
                                    </label>
                                    
                                    <label class="cursor-pointer border rounded-xl p-3 flex flex-col items-center justify-center gap-2 hover:bg-slate-50 transition-colors"
                                           :class="method === 'bank_transfer' ? 'border-orange-500 bg-orange-50 text-orange-700' : 'border-slate-200 text-slate-600'"
                                           @click="method = 'bank_transfer'">
                                        <input type="radio" name="method" value="bank_transfer" class="sr-only" x-model="method">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                        <span class="text-xs font-bold">Chuyển khoản</span>
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full py-3.5 bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-200 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all">
                                Process Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cashierState', () => ({
            paymentModalOpen: false,
            currentOrderId: null,
            currentOrderCode: '',
            balance: 0,
            amount: 0,
            method: 'cash',
            
            openPaymentModal(orderId, balance, code) {
                this.currentOrderId = orderId;
                this.currentOrderCode = code;
                this.balance = balance;
                this.amount = balance;
                this.method = 'cash';
                this.paymentModalOpen = true;
            },
            
            closePaymentModal() {
                this.paymentModalOpen = false;
            }
        }));
    });
</script>
@endsection
