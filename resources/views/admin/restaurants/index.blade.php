@extends('layouts.admin')

@section('header', 'Nhà hàng')

@section('content')
<div class="space-y-6">
    <!-- Action Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Danh sách nhà hàng</h2>
            <p class="text-sm text-slate-500">Quản lý thông tin và cài đặt cho các nhà hàng của bạn.</p>
        </div>
        <a href="{{ route('admin.restaurants.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Thêm nhà hàng
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nhà hàng</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Chi nhánh</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tiền tệ</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Màu sắc</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($restaurants as $restaurant)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center mr-3">
                                    <span class="text-orange-600 font-bold">{{ substr($restaurant->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">{{ $restaurant->name }}</div>
                                    <div class="text-xs text-slate-500">/{{ $restaurant->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-lg">
                                {{ $restaurant->branches_count }} Chi nhánh
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $restaurant->currency }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-6 h-6 rounded-full border border-slate-200" style="background-color: {{ $restaurant->theme_color }}"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.restaurants.destroy', $restaurant) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <h3 class="text-sm font-medium text-slate-900">Không tìm thấy nhà hàng nào</h3>
                                <p class="text-xs text-slate-500 mt-1">Bắt đầu bằng cách tạo nhà hàng đầu tiên của bạn.</p>
                                <a href="{{ route('admin.restaurants.create') }}" class="mt-4 text-orange-600 font-semibold text-sm hover:underline">Thêm nhà hàng</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($restaurants->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 text-slate-500 text-xs text-xs">
            {{ $restaurants->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
