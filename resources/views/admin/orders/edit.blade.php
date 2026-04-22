@extends('layouts.admin')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="text-slate-500 hover:text-slate-700 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-slate-900">Sửa đơn hàng: #{{ $order->order_code }}</h2>
    </div>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden max-w-2xl">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
        <h3 class="text-lg font-bold text-slate-900">Chi tiết trạng thái</h3>
        <span class="text-sm font-semibold text-slate-500">Bàn: {{ $order->table->name ?? 'N/A' }}</span>
    </div>
    
    <div class="p-6">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- General Status -->
            <div>
                <label for="status" class="block text-sm font-bold text-slate-700 mb-2">Trạng thái Thực đơn</label>
                <select name="status" id="status" class="w-full rounded-xl border-slate-300 bg-slate-50 py-3 px-4 text-slate-900 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition-colors cursor-pointer">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ duyệt (Pending)</option>
                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận (Confirmed)</option>
                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Đang nấu (Preparing)</option>
                    <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Sẵn sàng phục vụ (Ready)</option>
                    <option value="served" {{ $order->status == 'served' ? 'selected' : '' }}>Đã lên món (Served)</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành (Completed)</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy (Cancelled)</option>
                </select>
                @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Payment Status -->
            <div>
                <label for="payment_status" class="block text-sm font-bold text-slate-700 mb-2">Trạng thái Thanh toán</label>
                <select name="payment_status" id="payment_status" class="w-full rounded-xl border-slate-300 bg-slate-50 py-3 px-4 text-slate-900 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition-colors cursor-pointer">
                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chưa thanh toán (Pending)</option>
                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán (Paid)</option>
                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thất bại (Failed)</option>
                </select>
                @error('payment_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Note -->
            <div>
                <label for="note" class="block text-sm font-bold text-slate-700 mb-2">Ghi chú của Khách hàng</label>
                <textarea name="note" id="note" rows="3" class="w-full rounded-xl border-slate-300 bg-slate-50 py-3 px-4 text-slate-900 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition-colors" placeholder="Ghi chú thêm... (không bắt buộc)">{{ old('note', $order->note) }}</textarea>
                @error('note') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-200 hover:bg-orange-700 transition-all active:scale-95">
                    Lưu Thay Đổi
                </button>
                <a href="{{ route('admin.orders.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
