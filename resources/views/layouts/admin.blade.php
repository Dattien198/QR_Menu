<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'QR Menu Admin') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        .glass-sidebar { background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.05); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fafc] text-slate-900 antialiased" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 glass-sidebar text-white transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 shadow-2xl shadow-slate-900/50"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between px-6 py-5 bg-transparent border-b border-white/5">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-amber-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-orange-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-white">QRMenu<span class="text-orange-500">.</span></span>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
                    <x-nav-link-admin href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="dashboard">
                        Bảng điều khiển
                    </x-nav-link-admin>
                    
                    @hasanyrole('admin|superadmin|manager')
                        <div class="pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-4">QUẢN LÝ CHUNG</div>
                        <x-nav-link-admin href="{{ route('admin.restaurants.index') }}" :active="request()->routeIs('admin.restaurants.*')" icon="restaurant">Nhà hàng</x-nav-link-admin>
                        {{-- <x-nav-link-admin href="{{ route('admin.branches.index') }}" :active="request()->routeIs('admin.branches.*')" icon="branch">Chi nhánh</x-nav-link-admin> --}}
                        <x-nav-link-admin href="{{ route('admin.tables.index') }}" :active="request()->routeIs('admin.tables.*')" icon="table">Bàn & QR Code</x-nav-link-admin>
                        
                        <div class="pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-4">THỰC ĐƠN</div>
                        <x-nav-link-admin href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')" icon="category">Danh mục</x-nav-link-admin>
                        <x-nav-link-admin href="{{ route('admin.menu-items.index') }}" :active="request()->routeIs('admin.menu-items.*')" icon="food">Món ăn</x-nav-link-admin>
                    @endhasanyrole
                    
                    <div class="pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-4">VẬN HÀNH</div>
                    @hasanyrole('admin|superadmin|manager|cashier')
                        <x-nav-link-admin href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')" icon="order">Đơn hàng</x-nav-link-admin>
                        <x-nav-link-admin href="{{ route('cashier.index') }}" :active="request()->routeIs('cashier.*')" icon="dashboard">Thu ngân</x-nav-link-admin>
                    @endhasanyrole
                    
                    @hasanyrole('admin|superadmin|manager|kitchen')
                        <x-nav-link-admin href="{{ route('kitchen.index') }}" :active="request()->routeIs('kitchen.*')" icon="food">Bếp (KDS)</x-nav-link-admin>
                    @endhasanyrole
                    
                    <div class="pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-4">HỆ THỐNG</div>
                    <x-nav-link-admin href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.edit')" icon="user">Tài khoản</x-nav-link-admin>
                </nav>

                <!-- User Profile & Logout -->
                <div class="p-4 bg-slate-900/50 border-t border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center font-bold text-slate-300 border border-slate-600/50">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-orange-400 font-medium capitalize">{{ auth()->user()->roles->first()->name ?? 'User' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-all" title="Đăng xuất">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 relative z-0 overflow-y-auto custom-scrollbar focus:outline-none bg-[#f8fafc]">
            <!-- Topbar -->
            <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm">
                <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="text-slate-500 lg:hidden p-2 bg-slate-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <h1 class="text-xl font-bold text-slate-800 tracking-tight">@yield('header', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] uppercase tracking-wider font-semibold text-slate-500">{{ Auth::user()->roles->first()?->name ?? 'User' }}</p>
                        </div>
                        <div class="relative">
                            <img class="h-10 w-10 rounded-xl ring-4 ring-orange-50 object-cover shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=fff&background=f97316&bold=true" alt="Avatar">
                            <div class="absolute bottom-[-2px] right-[-2px] w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="py-6 px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
