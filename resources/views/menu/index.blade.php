@extends('layouts.menu')

@section('title', $restaurant->name . ' — Thực đơn')
@section('restaurant_name', $restaurant->name)
@section('table_name', ($table->branch->name ?? '') . ' · ' . $table->name)

@section('logo')
    @if($restaurant->logo)
        <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}" class="w-full h-full object-cover">
    @else
        <span class="text-white font-extrabold text-lg leading-none">{{ strtoupper(substr($restaurant->name, 0, 2)) }}</span>
    @endif
@endsection

@section('header_actions')
    <button @click="openOrders()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition hidden lg:flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Đơn của tôi
        <span x-show="orders.length > 0" class="ml-1 bg-tc text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full" x-text="orders.length"></span>
    </button>
@endsection

@section('styles')
<style>
    :root {
        --tc: {{ $restaurant->theme_color ?? '#f97316' }};
        --tc-r: {{ hexdec(substr($restaurant->theme_color ?? '#f97316', 1, 2)) }};
        --tc-g: {{ hexdec(substr($restaurant->theme_color ?? '#f97316', 3, 2)) }};
        --tc-b: {{ hexdec(substr($restaurant->theme_color ?? '#f97316', 5, 2)) }};
    }
    .tc          { color: var(--tc) !important; }
    .bg-tc       { background-color: var(--tc) !important; }
    .bg-tc-soft  { background-color: rgba(var(--tc-r),var(--tc-g),var(--tc-b),.1) !important; }
    .border-tc   { border-color: var(--tc) !important; }
    .ring-tc     { --tw-ring-color: rgba(var(--tc-r),var(--tc-g),var(--tc-b),.4); }
    .shadow-tc   { box-shadow: 0 8px 24px -4px rgba(var(--tc-r),var(--tc-g),var(--tc-b),.35); }

    /* Skeleton */
    @keyframes shimmer { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
    .skeleton { background: linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);
                background-size: 200% 100%; animation: shimmer 1.4s infinite; border-radius:.5rem; }
    
    .category-section { scroll-margin-top: 80px; }
    
    /* Card animation */
    .menu-card { transition: transform .2s cubic-bezier(.4,0,.2,1), box-shadow .2s; }
    .menu-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
    .menu-card:active { transform: scale(.98); }
</style>
@endsection

{{-- ── SIDEBAR (Desktop) ── --}}
@section('sidebar')
<div class="py-6 px-4 space-y-2">
    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-2">Danh mục</div>
    <template x-for="cat in visibleCategories" :key="cat.id">
        <button @click="scrollToCategory(cat.id)"
                :class="activeCategory === cat.id ? 'bg-tc-soft tc font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all text-sm">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-lg" x-text="getCategoryIcon(cat.name)">🍽️</span>
                <span x-text="cat.name"></span>
            </div>
            <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full" x-text="cat.menu_items.length"></span>
        </button>
    </template>
</div>
@endsection

{{-- ── MAIN CONTENT (Menu Items Grid) ── --}}
@section('content')
<div class="p-6 max-w-5xl mx-auto">
    {{-- Search & Mobile Categories --}}
    <div class="mb-8">
        <div class="relative max-w-xl mx-auto lg:max-w-none">
            <svg class="absolute left-4 top-3.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input x-model.debounce.300ms="search" type="search" placeholder="Tìm món ăn..."
                   class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-2xl shadow-sm text-sm focus:outline-none focus:ring-2 ring-tc transition">
        </div>
        
        {{-- Quick filters --}}
        <div class="mt-4 flex gap-2 overflow-x-auto hide-scroll pb-2">
            <template x-for="f in filters" :key="f">
                <button @click="activeFilter = f"
                        :class="activeFilter === f ? 'bg-tc text-white shadow-tc' : 'bg-white text-slate-600 border border-slate-200'"
                        class="px-4 py-1.5 rounded-xl text-sm font-semibold whitespace-nowrap transition-all duration-200"
                        x-text="f"></button>
            </template>
        </div>

        {{-- Mobile Top Tabs (Visible only on lg:hidden) --}}
        <div class="lg:hidden mt-4 bg-white/90 backdrop-blur rounded-2xl shadow-sm border border-slate-100 p-1">
            <ul class="flex overflow-x-auto hide-scroll gap-1">
                <template x-for="cat in visibleCategories" :key="cat.id">
                    <li>
                        <button @click="scrollToCategory(cat.id)"
                                :class="activeCategory === cat.id ? 'bg-tc text-white shadow-sm' : 'text-slate-600'"
                                class="text-sm px-4 py-2 rounded-xl whitespace-nowrap transition-colors font-medium"
                                x-text="cat.name"></button>
                    </li>
                </template>
            </ul>
        </div>
    </div>

    {{-- Menu Grid --}}
    <div class="space-y-12">
        <template x-for="cat in visibleCategories" :key="cat.id">
            <div :id="'cat-' + cat.id" class="category-section" x-intersect.half="activeCategory = cat.id">
                
                <h2 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <span x-text="cat.name"></span>
                    <div class="h-px bg-slate-200 flex-grow"></div>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    <template x-for="item in filteredItems(cat)" :key="item.id">
                        <div @click="openItem(item)"
                             class="menu-card bg-white rounded-2xl shadow-sm border border-slate-100 cursor-pointer overflow-hidden flex flex-col h-full relative group"
                             :class="item.status === 'out_of_stock' ? 'opacity-60 grayscale-[0.3]' : ''">
                             
                            {{-- Image container (Top) --}}
                            <div class="h-48 bg-slate-100 relative overflow-hidden shrink-0">
                                <div x-show="item.is_featured" class="absolute top-2 left-2 bg-tc text-white text-[10px] font-bold px-2 py-1 rounded-lg z-20 shadow-md">
                                    ⭐ NỔI BẬT
                                </div>
                                <div x-show="item.status === 'out_of_stock'" class="absolute inset-0 bg-white/70 flex items-center justify-center z-20 backdrop-blur-[2px]">
                                    <span class="bg-slate-800 text-white text-xs font-bold px-3 py-1 rounded-full">HẾT HÀNG</span>
                                </div>
                                
                                <template x-if="item.images && item.images.length > 0">
                                    <img :src="'/storage/' + item.images[0]" :alt="item.name" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy">
                                </template>
                                <template x-if="!item.images || item.images.length === 0">
                                    <div class="w-full h-full flex items-center justify-center bg-slate-50">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                </template>
                            </div>

                            {{-- Content --}}
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="text-base font-bold text-slate-900 leading-tight mb-1" x-text="item.name"></h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mb-3 flex-1" x-text="item.description || ''"></p>
                                
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-lg font-black tc" x-text="fmt(item.price)"></span>
                                    <button @click.stop="quickAdd(item)"
                                            :disabled="item.status === 'out_of_stock'"
                                            class="w-10 h-10 rounded-xl bg-tc-soft tc flex items-center justify-center hover:bg-tc hover:text-white transition-all active:scale-90 disabled:opacity-40">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
        
        {{-- Empty Search --}}
        <div x-show="visibleCategories.every(c => filteredItems(c).length === 0)" x-cloak class="py-20 text-center">
            <span class="text-6xl mb-4 block">🔍</span>
            <p class="text-slate-500 font-medium text-lg">Không tìm thấy món nào</p>
            <button @click="search = ''; activeFilter = 'Tất cả'" class="mt-4 px-6 py-2 bg-slate-100 rounded-full text-sm tc font-bold hover:bg-slate-200 transition">Xóa bộ lọc</button>
        </div>
    </div>
</div>
@endsection

{{-- ── CART PANEL (Right Sidebar Desktop) ── --}}
@section('cart_panel')
<div class="flex flex-col h-full bg-slate-50/50">
    <div class="p-5 border-b border-slate-200 bg-white">
        <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
            <span>🛒</span> Giỏ hàng
            <span x-show="cartTotal.qty > 0" class="ml-auto bg-tc text-white text-xs font-bold px-2 py-1 rounded-full" x-text="cartTotal.qty"></span>
        </h2>
    </div>

    {{-- Cart Items --}}
    <div class="flex-1 overflow-y-auto px-4 py-4 hide-scroll" id="cart-content-container">
        <template x-if="cart.length === 0">
            <div class="h-full flex flex-col items-center justify-center text-slate-400 space-y-4">
                <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <p class="font-medium text-sm">Chưa có món nào</p>
            </div>
        </template>

        <div class="space-y-4">
            <template x-for="(line, idx) in cart" :key="idx">
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex gap-3 items-center group relative">
                    <button @click="cart.splice(idx, 1); saveCart()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition shadow-sm">✕</button>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 leading-tight" x-text="line.name"></p>
                        <p x-show="line.note" class="text-xs text-slate-500 italic mt-0.5 line-clamp-1" x-text="line.note"></p>
                        <p class="text-sm font-extrabold tc mt-1" x-text="fmt(line.price * line.qty)"></p>
                    </div>
                    <div class="flex flex-col items-center gap-1.5 bg-slate-50 border border-slate-100 rounded-xl p-1 shrink-0">
                        <button @click="changeQty(idx, +1)" class="w-7 h-7 bg-white rounded-lg shadow-sm flex items-center justify-center text-slate-700 active:scale-90 transition"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg></button>
                        <span class="w-7 text-center text-sm font-bold text-slate-900" x-text="line.qty"></span>
                        <button @click="changeQty(idx, -1)" class="w-7 h-7 bg-white rounded-lg shadow-sm flex items-center justify-center text-slate-700 active:scale-90 transition"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg></button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Form fields --}}
        <div x-show="cart.length > 0" class="mt-6 space-y-3">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide px-1">Thông tin đơn</h3>
            
            <div class="relative">
                <select x-model="selectedTableId" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-orange-600 focus:outline-none focus:ring-2 ring-tc transition appearance-none shadow-sm">
                    <template x-for="tbl in allTables" :key="tbl.id">
                        <option :value="tbl.id" x-text="tbl.name + (tbl.status !== 'empty' && tbl.id != selectedTableId ? ' (Đang có khách)' : '')" :disabled="tbl.status !== 'empty' && tbl.id != selectedTableId"></option>
                    </template>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
            </div>

            <input x-model="customerName" type="text" placeholder="Tên khách hàng (tuỳ chọn)"
                   class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 ring-tc transition shadow-sm">
            <textarea x-model="orderNote" rows="2" placeholder="Ghi chú chung..."
                      class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm resize-none focus:outline-none focus:ring-2 ring-tc transition shadow-sm"></textarea>
        </div>
    </div>

    {{-- Footer --}}
    <div class="p-5 bg-white border-t border-slate-200" id="cart-footer-container">
        <div x-show="cart.length > 0" class="mb-4">
            <div class="flex justify-between items-end mb-1">
                <span class="text-sm font-bold text-slate-500">Tổng cộng</span>
                <span class="text-2xl font-black tc" x-text="fmt(cartTotal.amount)"></span>
            </div>
            @if(($restaurant->vat ?? 0) > 0)
                <div class="text-right text-xs text-slate-400">Đã bao gồm VAT {{ $restaurant->vat }}%</div>
            @endif
        </div>
        
        <button @click="submitOrder()"
                :disabled="cart.length === 0 || submitting"
                class="w-full h-14 bg-tc text-white font-bold rounded-2xl flex items-center justify-center gap-2 active:scale-95 transition shadow-tc disabled:opacity-50 disabled:cursor-not-allowed">
            <svg x-show="submitting" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            <span x-text="submitting ? 'Đang xử lý...' : 'GỬI ĐƠN LÊN BẾP'"></span>
        </button>
    </div>
</div>
@endsection

{{-- ── MOBILE FLOATING NAV (Visible only on mobile) ── --}}
@section('mobile_float')
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur border-t border-slate-200 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] pb-safe">
    <div class="flex justify-around items-center px-2 py-2">
        <button @click="window.scrollTo({top:0, behavior:'smooth'})" class="flex flex-col items-center p-2 text-tc w-16">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
            <span class="text-[10px] font-bold">Menu</span>
        </button>

        <button @click="cartOpen = true" class="flex flex-col items-center justify-center -mt-8 relative group">
            <div class="w-14 h-14 bg-tc rounded-full shadow-tc flex items-center justify-center border-4 border-white group-active:scale-95 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <span x-show="cartTotal.qty > 0" class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white shadow-sm" x-text="cartTotal.qty"></span>
            <span class="text-[10px] font-bold text-slate-500 mt-1">Giỏ hàng</span>
        </button>

        <button @click="openOrders()" class="flex flex-col items-center p-2 text-slate-400 w-16">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span class="text-[10px] font-bold">Đơn hàng</span>
        </button>
    </div>
</nav>

{{-- Mobile Cart Sheet overlay --}}
<template x-teleport="body">
    <div x-show="cartOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex flex-col justify-end">
        <div x-show="cartOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="cartOpen = false"></div>
        <div x-show="cartOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="relative bg-white rounded-t-3xl shadow-2xl h-[85vh] flex flex-col overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white rounded-t-3xl shrink-0">
                <h2 class="font-extrabold text-lg flex items-center gap-2">🛒 Giỏ hàng</h2>
                <button @click="cartOpen = false" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500">✕</button>
            </div>
            
            {{-- We duplicate the cart content here for mobile specifically --}}
            <div class="flex-1 overflow-y-auto px-4 py-4 hide-scroll bg-slate-50">
                <template x-if="cart.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-slate-400 space-y-4">
                        <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <p class="font-medium text-sm">Chưa có món nào</p>
                    </div>
                </template>

                <div class="space-y-4">
                    <template x-for="(line, idx) in cart" :key="idx">
                        <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex gap-3 items-center">
                            <button @click="cart.splice(idx, 1); saveCart()" class="w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs shadow-sm">✕</button>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 leading-tight" x-text="line.name"></p>
                                <p x-show="line.note" class="text-xs text-slate-500 italic mt-0.5 line-clamp-1" x-text="line.note"></p>
                                <p class="text-sm font-extrabold tc mt-1" x-text="fmt(line.price * line.qty)"></p>
                            </div>
                            <div class="flex flex-col items-center gap-1.5 bg-slate-50 border border-slate-100 rounded-xl p-1 shrink-0">
                                <button @click="changeQty(idx, +1)" class="w-7 h-7 bg-white rounded-lg shadow-sm flex items-center justify-center text-slate-700 active:scale-90 transition"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg></button>
                                <span class="w-7 text-center text-sm font-bold text-slate-900" x-text="line.qty"></span>
                                <button @click="changeQty(idx, -1)" class="w-7 h-7 bg-white rounded-lg shadow-sm flex items-center justify-center text-slate-700 active:scale-90 transition"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg></button>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="cart.length > 0" class="mt-6 space-y-3">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide px-1">Thông tin đơn</h3>
                    <div class="relative">
                        <select x-model="selectedTableId" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-orange-600 focus:outline-none focus:ring-2 ring-tc transition appearance-none shadow-sm">
                            <template x-for="tbl in allTables" :key="tbl.id">
                                <option :value="tbl.id" x-text="tbl.name + (tbl.status !== 'empty' && tbl.id != selectedTableId ? ' (Đang có khách)' : '')" :disabled="tbl.status !== 'empty' && tbl.id != selectedTableId"></option>
                            </template>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                    <input x-model="customerName" type="text" placeholder="Tên khách hàng (tuỳ chọn)" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 ring-tc transition shadow-sm">
                    <textarea x-model="orderNote" rows="2" placeholder="Ghi chú chung..." class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm resize-none focus:outline-none focus:ring-2 ring-tc transition shadow-sm"></textarea>
                </div>
            </div>

            <div class="p-5 bg-white border-t border-slate-200 shrink-0 pb-safe">
                <div x-show="cart.length > 0" class="mb-4">
                    <div class="flex justify-between items-end mb-1">
                        <span class="text-sm font-bold text-slate-500">Tổng cộng</span>
                        <span class="text-2xl font-black tc" x-text="fmt(cartTotal.amount)"></span>
                    </div>
                    @if(($restaurant->vat ?? 0) > 0)
                        <div class="text-right text-xs text-slate-400">Đã bao gồm VAT {{ $restaurant->vat }}%</div>
                    @endif
                </div>
                <button @click="submitOrder()" :disabled="cart.length === 0 || submitting" class="w-full h-14 bg-tc text-white font-bold rounded-2xl flex items-center justify-center gap-2 active:scale-95 transition shadow-tc disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg x-show="submitting" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span x-text="submitting ? 'Đang xử lý...' : 'GỬI ĐƠN LÊN BẾP'"></span>
                </button>
            </div>
        </div>
    </div>
</template>
@endsection

{{-- ── MODALS & SCRIPTS ── --}}
@section('scripts')
{{-- ITEM DETAIL MODAL (Desktop Dialog) --}}
<template x-teleport="body">
    <div x-show="selectedItem" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="selectedItem" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeItem()"></div>
        <div x-show="selectedItem" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col md:flex-row overflow-hidden">
            
            <button @click="closeItem()" class="absolute top-4 right-4 z-10 w-10 h-10 bg-black/20 hover:bg-black/40 backdrop-blur text-white rounded-full flex items-center justify-center transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>

            <template x-if="selectedItem">
                <div class="flex flex-col md:flex-row w-full h-full">
                    {{-- Image side --}}
                    <div class="md:w-1/2 h-64 md:h-auto bg-slate-100 relative shrink-0">
                        <template x-if="selectedItem.images && selectedItem.images.length > 0">
                            <img :src="'/storage/' + selectedItem.images[0]" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!selectedItem.images || selectedItem.images.length === 0">
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </template>
                    </div>
                    
                    {{-- Info side --}}
                    <div class="md:w-1/2 p-6 md:p-8 flex flex-col flex-1 overflow-y-auto hide-scroll">
                        <h3 class="text-3xl font-black text-slate-900 leading-tight mb-2" x-text="selectedItem.name"></h3>
                        <div class="text-2xl font-black tc mb-4" x-text="fmt(selectedItem.price)"></div>
                        
                        <p x-show="selectedItem.description" class="text-sm text-slate-600 leading-relaxed mb-6" x-text="selectedItem.description"></p>
                        
                        <div class="mt-auto space-y-6 pt-4 border-t border-slate-100">
                            <div>
                                <label class="text-sm font-bold text-slate-800 block mb-2">Ghi chú cho món này</label>
                                <textarea x-model="itemNote" rows="2" placeholder="VD: Không hành, ít cay..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 ring-tc transition resize-none"></textarea>
                            </div>
                            
                            <div class="flex gap-4">
                                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-1.5 h-14 shrink-0">
                                    <button @click="if(itemQty>1)itemQty--" class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center font-bold active:scale-95 text-slate-700">-</button>
                                    <span class="w-12 text-center font-bold text-lg text-slate-900" x-text="itemQty"></span>
                                    <button @click="itemQty++" class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center font-bold active:scale-95 text-slate-700">+</button>
                                </div>
                                <button @click="addToCart()" :disabled="selectedItem.status === 'out_of_stock'" class="flex-1 h-14 bg-tc text-white font-bold rounded-2xl shadow-tc active:scale-95 transition flex items-center justify-center gap-2 disabled:opacity-50">
                                    <span x-text="selectedItem.status === 'out_of_stock' ? 'HẾT HÀNG' : 'Thêm vào đơn'"></span>
                                    <span x-show="selectedItem.status !== 'out_of_stock'" x-text="'• ' + fmt(selectedItem.price * itemQty)"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

{{-- ORDERS MODAL --}}
<template x-teleport="body">
    <div x-show="ordersOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="ordersOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="ordersOpen = false"></div>
        <div x-show="ordersOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
            
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-white z-10">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">Đơn hàng của tôi</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Cập nhật tự động mỗi 30 giây</p>
                </div>
                <button @click="ordersOpen = false" class="w-10 h-10 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            <div class="flex-1 overflow-y-auto p-5 bg-slate-50 space-y-4">
                <template x-if="ordersLoading && orders.length === 0">
                    <div class="space-y-4">
                        <template x-for="i in [1,2]" :key="i">
                            <div class="bg-white rounded-2xl border border-slate-100 p-4 space-y-3 animate-pulse">
                                <div class="flex justify-between">
                                    <div class="h-4 w-28 bg-slate-200 rounded"></div>
                                    <div class="h-6 w-20 bg-slate-200 rounded-full"></div>
                                </div>
                                <div class="h-3 w-full bg-slate-100 rounded"></div>
                                <div class="h-3 w-3/4 bg-slate-100 rounded"></div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="!ordersLoading && orders.length === 0">
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p class="text-slate-600 font-bold text-lg">Chưa có đơn hàng nào</p>
                        <p class="text-slate-400 text-sm mt-1">Hãy chọn món và đặt hàng để theo dõi trạng thái</p>
                        <button @click="ordersOpen = false" class="mt-5 px-6 py-2.5 bg-tc text-white font-bold rounded-xl text-sm shadow-tc active:scale-95 transition">Xem thực đơn</button>
                    </div>
                </template>
                
                <template x-if="orders.length > 0">
                    <template x-for="order in orders" :key="order.id">
                        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
                            {{-- Order Header --}}
                            <div class="p-4 flex justify-between items-start">
                                <div>
                                    <span class="font-extrabold text-slate-800 text-base" x-text="'Đơn #' + order.order_code"></span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-xs text-slate-400" x-text="order.created_at"></span>
                                    </div>
                                </div>
                                {{-- Dynamic status badge by order status --}}
                                <span class="text-xs font-bold px-3 py-1.5 rounded-full"
                                    :class="{
                                        'bg-amber-100 text-amber-700': order.status === 'pending',
                                        'bg-blue-100 text-blue-700': order.status === 'confirmed',
                                        'bg-orange-100 text-orange-700': order.status === 'preparing',
                                        'bg-green-100 text-green-700': order.status === 'ready',
                                        'bg-slate-100 text-slate-600': order.status === 'served',
                                    }"
                                    x-text="order.status_label"
                                ></span>
                            </div>

                            {{-- Status Progress Bar --}}
                            <div class="px-4 pb-4">
                                <div class="flex items-center gap-1">
                                    <template x-for="(step, si) in [
                                        {key:'pending', label:'Chờ'},
                                        {key:'confirmed', label:'Xác nhận'},
                                        {key:'preparing', label:'Làm'},
                                        {key:'ready', label:'Sẵn sàng'},
                                        {key:'served', label:'Phục vụ'}
                                    ]" :key="si">
                                        <div class="flex flex-col items-center flex-1">
                                            <div class="w-full h-1.5 rounded-full mb-1"
                                                :class="getStepIndex(order.status) >= si ? 'bg-tc' : 'bg-slate-200'"
                                            ></div>
                                            <span class="text-[9px] font-bold"
                                                :class="getStepIndex(order.status) >= si ? 'tc' : 'text-slate-300'"
                                                x-text="step.label"
                                            ></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Order Items --}}
                            <div class="border-t border-slate-100 divide-y divide-slate-50">
                                <template x-for="item in order.items" :key="item.name">
                                    <div class="px-4 py-3 flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-tc-soft tc font-extrabold flex items-center justify-center shrink-0 text-sm" x-text="item.quantity"></div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-800 leading-snug" x-text="item.name"></p>
                                            <p x-show="item.note" class="text-xs text-slate-400 italic mt-0.5 truncate" x-text="item.note"></p>
                                        </div>
                                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-lg shrink-0"
                                            :class="{
                                                'bg-amber-100 text-amber-600': item.status === 'pending',
                                                'bg-orange-100 text-orange-600': item.status === 'preparing',
                                                'bg-green-100 text-green-600': item.status === 'ready',
                                                'bg-slate-100 text-slate-500': item.status === 'served',
                                            }"
                                            x-text="{pending:'⏳ Chờ',preparing:'👨‍🍳 Đang làm',ready:'✅ Xong',served:'🍽️ Đã phục vụ'}[item.status] || item.status"
                                        ></span>
                                    </div>
                                </template>
                            </div>

                            {{-- Total --}}
                            <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-500">Tổng cộng</span>
                                <span class="text-lg font-black tc" x-text="fmt(order.total)"></span>
                            </div>
                        </div>
                    </template>
                </template>
            </div>
            
            <div class="p-4 border-t border-slate-100 bg-white z-10">
                <button @click="refreshOrders()" :class="ordersLoading ? 'opacity-60 cursor-wait' : ''" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition flex items-center justify-center gap-2">
                    <svg :class="ordersLoading ? 'animate-spin' : ''" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span x-text="ordersLoading ? 'Đang làm mới...' : 'Làm mới trạng thái'"></span>
                </button>
            </div>
        </div>
    </div>
</template>

{{-- TOAST (Top Right) --}}
<template x-teleport="body">
    <div x-show="toast.show" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-1rem]" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-[-1rem]" class="fixed top-6 right-6 z-[100] max-w-sm">
        <div :class="toast.type === 'success' ? 'bg-slate-900' : 'bg-red-500'" class="text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3">
            <span class="text-xl" x-text="toast.type === 'success' ? '✨' : '⚠️'"></span>
            <p class="font-medium text-sm flex-1" x-text="toast.message"></p>
        </div>
    </div>
</template>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
<script>
const CURRENCY = '{{ $restaurant->currency ?? "VND" }}';
const TABLE_TOKEN = '{{ $table->qr_token }}';
const CART_KEY = 'qr_cart_' + TABLE_TOKEN;
const ORDERS_URL = "{{ route('menu.session-orders', ['restaurant' => $restaurant->slug, 'table' => $table->qr_token]) }}";
const ORDER_URL = "{{ route('menu.store-order', ['restaurant' => $restaurant->slug, 'table' => $table->qr_token]) }}";
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

const fmtNum = new Intl.NumberFormat('vi-VN', { style: CURRENCY === 'VND' ? 'decimal' : 'currency', currency: CURRENCY, maximumFractionDigits: 0 });
function fmt(v) { return CURRENCY === 'VND' ? fmtNum.format(v) + ' ₫' : fmtNum.format(v); }

document.addEventListener('alpine:init', () => {
    Alpine.data('menuApp', () => ({
        categories: @json($categories),
        allTables: @json($allTables),
        filters: ['Tất cả', 'Nổi bật', 'Món chay', 'Cay', 'Đang có'],
        activeFilter: 'Tất cả',
        search: '',
        activeCategory: null,
        
        selectedItem: null, itemQty: 1, itemNote: '',
        
        cart: [], cartOpen: false, selectedTableId: {{ $table->id }}, customerName: '', orderNote: '', submitting: false,
        
        orders: @json($sessionOrders), ordersOpen: false, ordersLoading: false,
        toast: { show: false, message: '', type: 'success' },

        get visibleCategories() { return this.categories.filter(c => c.menu_items && c.menu_items.length > 0); },
        get cartTotal() { return { qty: this.cart.reduce((s, l) => s + l.qty, 0), amount: this.cart.reduce((s, l) => s + l.price * l.qty, 0) }; },

        init() {
            try { this.cart = JSON.parse(localStorage.getItem(CART_KEY) || '[]'); } catch { this.cart = []; }
            this.activeCategory = this.visibleCategories[0]?.id || null;
            
            // Listen to global events if needed
            window.addEventListener('open-orders', () => this.openOrders());
            window.addEventListener('call-waiter', () => this.callWaiter());
            
            @if(request()->query('ordered')) setTimeout(() => this.openOrders(), 600); @endif
            
            setInterval(() => { if (this.ordersOpen) this.refreshOrders(); }, 30000);
        },

        filteredItems(cat) {
            return (cat.menu_items || []).filter(item => {
                if (this.search) {
                    const q = this.search.toLowerCase();
                    if (!item.name.toLowerCase().includes(q) && !(item.description || '').toLowerCase().includes(q)) return false;
                }
                switch (this.activeFilter) {
                    case 'Nổi bật': return item.is_featured;
                    case 'Món chay': return (item.tags || '').toLowerCase().includes('chay');
                    case 'Cay': return (item.tags || '').toLowerCase().includes('cay');
                    case 'Đang có': return item.status === 'available';
                }
                return true;
            });
        },

        scrollToCategory(id) {
            this.activeCategory = id;
            const el = document.getElementById('cat-' + id);
            if (el) {
                const y = el.getBoundingClientRect().top + window.scrollY - 80;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        },
        
        getCategoryIcon(name) {
            const map = { 'khai vị': '🥗', 'chính': '🍛', 'nước': '🥤', 'tráng miệng': '🍰', 'lẩu': '🍲', 'nướng': '🥩', 'combo': '🍱' };
            const lower = name.toLowerCase();
            for (const [k, v] of Object.entries(map)) if (lower.includes(k)) return v;
            return '🍽️';
        },

        openItem(item) { this.selectedItem = item; this.itemQty = 1; this.itemNote = ''; },
        closeItem() { this.selectedItem = null; },

        quickAdd(item) { this.selectedItem = item; this.itemQty = 1; this.itemNote = ''; this.addToCart(); },
        addToCart() {
            if (!this.selectedItem) return;
            const existing = this.cart.find(l => l.id === this.selectedItem.id && l.note === this.itemNote);
            if (existing) existing.qty += this.itemQty;
            else this.cart.push({ id: this.selectedItem.id, name: this.selectedItem.name, price: this.selectedItem.price, qty: this.itemQty, note: this.itemNote });
            this.saveCart();
            this.selectedItem = null;
            this.showToast('Đã thêm vào giỏ hàng');
            if (navigator.vibrate) navigator.vibrate(50);
        },
        changeQty(idx, delta) {
            this.cart[idx].qty += delta;
            if (this.cart[idx].qty <= 0) this.cart.splice(idx, 1);
            if (this.cart.length === 0) this.cartOpen = false;
            this.saveCart();
        },
        saveCart() { localStorage.setItem(CART_KEY, JSON.stringify(this.cart)); },

        async submitOrder() {
            if (this.cart.length === 0 || this.submitting) return;
            this.submitting = true;
            try {
                const res = await fetch(ORDER_URL, {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ cart: this.cart, table_id: this.selectedTableId, customer_name: this.customerName, note: this.orderNote })
                });
                const data = await res.json();
                if (data.success) {
                    this.cart = []; this.saveCart(); this.cartOpen = false;
                    this.showToast('Đã gửi đơn #' + data.order_code);
                    await this.refreshOrders();
                    setTimeout(() => { this.ordersOpen = true; }, 500);
                } else this.showToast(data.message, 'error');
            } catch { this.showToast('Lỗi kết nối', 'error'); } 
            finally { this.submitting = false; }
        },

        async openOrders() { this.ordersOpen = true; await this.refreshOrders(); },
        async refreshOrders() {
            this.ordersLoading = true;
            try {
                const res = await fetch(ORDERS_URL);
                this.orders = (await res.json()).orders || [];
            } catch {} finally { this.ordersLoading = false; }
        },
        getStepIndex(status) {
            const steps = ['pending','confirmed','preparing','ready','served'];
            return steps.indexOf(status);
        },

        fmt, showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => { this.toast.show = false; }, 3000);
        }
    }));
});
</script>
@endsection
