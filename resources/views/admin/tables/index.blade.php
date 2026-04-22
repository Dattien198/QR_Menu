@extends('layouts.admin')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Bàn & Mã QR</h2>
            <p class="text-sm text-slate-500">Quản lý sơ đồ chỗ ngồi và tạo mã QR gọi món.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.tables.generate-all') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors">
                🔄 Tạo lại tất cả QR
            </a>
            <a href="{{ route('admin.tables.create') }}" class="px-4 py-2 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors shadow-lg">
                + Thêm bàn
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-100 rounded-xl text-green-700 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tables as $table)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:border-orange-200 transition-all group">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-orange-50 rounded-xl text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="flex items-center space-x-2 text-xs font-bold uppercase">
                            <span class="px-2 py-1 rounded bg-slate-100 text-slate-500">{{ $table->floor ?? 'Tầng 1' }}</span>
                            <span class="px-2 py-1 rounded {{ $table->status === 'occupied' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                {{ $table->status === 'empty' ? 'Còn trống' : ($table->status === 'occupied' ? 'Đang có khách' : $table->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-900 mb-1 leading-tight">{{ $table->name }}</h3>
                    <p class="text-xs text-slate-500 mb-4">{{ $table->branch->name }} • Sức chứa: {{ $table->capacity }} ng.</p>

                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex items-center justify-center mb-4 relative overflow-hidden">
                        @if($table->qr_token)
                            <img src="{{ route('qr.show', $table->qr_token) }}" class="w-32 h-32 relative z-10">
                        @else
                            <div class="w-32 h-32 flex items-center justify-center text-slate-300 border-2 border-dashed border-slate-200 rounded-lg">
                                Chưa có QR
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2">
                        <a href="{{ route('qr.show', $table->qr_token) }}" download="QR_{{ $table->qr_token }}.svg" class="flex-1 px-3 py-2 bg-orange-50 text-orange-600 text-center rounded-lg text-xs font-bold hover:bg-orange-600 hover:text-white transition-colors">
                            Tải mã QR
                        </a>
                        <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" onsubmit="return confirm('Xóa bàn này?')" class="block">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="mb-4 text-slate-300 flex justify-center">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Không tìm thấy bàn nào</h3>
                <p class="text-slate-500 mb-6">Bắt đầu bằng cách thêm bàn mới để tạo mã QR duy nhất.</p>
                <a href="{{ route('admin.tables.create') }}" class="px-6 py-2 bg-orange-600 text-white rounded-xl font-bold shadow-lg shadow-orange-200">Thêm bàn</a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $tables->links() }}
    </div>
</div>
@endsection
