@extends('layouts.admin')

@section('header', 'Quản lý đơn hàng')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Mã đơn</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bàn</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Thanh toán</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Tổng tiền</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($orders as $order)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">#{{ $order->order_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">{{ $order->table->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-md tracking-wider
                            @if($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'ready') bg-teal-100 text-teal-800
                            @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                            @else bg-blue-100 text-blue-800 @endif
                        ">{{ $order->status === 'pending' ? 'Chờ duyệt' : 
                          ($order->status === 'completed' ? 'Hoàn thành' : 
                          ($order->status === 'ready' ? 'Sẵn sàng phục vụ' : 
                          ($order->status === 'preparing' ? 'Đang nấu' : 
                          ($order->status === 'served' ? 'Đã lên món' : $order->status)))) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded border
                            @if($order->payment_status === 'paid') border-green-200 text-green-600
                            @else border-red-200 text-red-600 @endif
                        ">{{ $order->payment_status === 'paid' ? 'Đã trả' : 'Chưa trả' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900 text-right">{{ number_format($order->total, 0) }}đ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-slate-600 font-bold hover:text-orange-600 transition-colors">Xem</a>
                            <a href="{{ route('admin.orders.edit', $order) }}" class="text-blue-600 font-bold hover:text-blue-800 transition-colors">Sửa</a>
                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này? Toàn bộ chi tiết hóa đơn cũng sẽ bị xóa theo!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 font-bold hover:text-red-700 transition-colors">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-500">Không tìm thấy đơn hàng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $orders->links() }}
    </div>
</div>
@endsection
