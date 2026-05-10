<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f97316">

    <title>@yield('title', 'Thực đơn — QR Menu')</title>
    <meta name="description" content="Xem thực đơn và đặt món ngay, không cần cài ứng dụng.">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">

    {{-- Vite assets (Tailwind + Alpine) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Be Vietnam Pro', sans-serif; background: #f8f9fb; }
        [x-cloak] { display: none !important; }

        /* ── Desktop 3-column shell ── */
        .menu-shell {
            display: grid;
            grid-template-columns: 230px 1fr 340px;
            grid-template-rows: auto 1fr;
            min-height: 100vh;
        }

        /* Header spans all columns */
        .menu-header {
            grid-column: 1 / -1;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .menu-sidebar {
            grid-column: 1;
            position: sticky;
            top: 64px; /* header height */
            height: calc(100vh - 64px);
            overflow-y: auto;
            border-right: 1px solid #e2e8f0;
            background: #fff;
            scrollbar-width: thin;
            scrollbar-color: #e2e8f0 transparent;
        }
        .menu-sidebar::-webkit-scrollbar { width: 4px; }
        .menu-sidebar::-webkit-scrollbar-track { background: transparent; }
        .menu-sidebar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        .menu-main {
            grid-column: 2;
            min-height: calc(100vh - 64px);
            overflow-y: auto;
        }

        .menu-cart {
            grid-column: 3;
            position: sticky;
            top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
            border-left: 1px solid #e2e8f0;
            background: #fff;
        }

        /* ── Responsive: mobile collapses to 1 column ── */
        @media (max-width: 1024px) {
            .menu-shell {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto 1fr auto;
            }
            .menu-header  { grid-column: 1; }
            .menu-sidebar { display: none; } /* hidden on mobile — tabs used instead */
            .menu-main    { grid-column: 1; }
            .menu-cart    { display: none; } /* cart as floating button on mobile */
        }

        /* Scrollbar hide utility */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @yield('styles')
</head>
<body class="antialiased" x-data="menuApp()" x-init="init()">

<div class="menu-shell">

    {{-- ── Header ── --}}
    <header class="menu-header bg-white/95 backdrop-blur-xl border-b border-slate-200/70 shadow-sm">
        <div class="px-6 h-16 flex items-center justify-between gap-4">

            {{-- Left: Logo + restaurant name + table --}}
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shrink-0 shadow-sm">
                    @yield('logo', '<span class="text-white font-extrabold text-base">QR</span>')
                </div>
                <div class="min-w-0">
                    <h1 class="text-base font-extrabold text-slate-900 truncate leading-tight">
                        @yield('restaurant_name', 'Nhà hàng')
                    </h1>
                    <p class="text-[11px] font-semibold text-slate-500 truncate flex items-center gap-1">
                        <svg class="w-3 h-3 inline shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M3 10l9-7 9 7v11a1 1 0 01-1 1H4a1 1 0 01-1-1V10z"/>
                        </svg>
                        @yield('table_name', 'Bàn --')
                    </p>
                </div>
            </div>

            {{-- Right: Status + call waiter btn --}}
            <div class="flex items-center gap-3 shrink-0">
                <div class="hidden sm:flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-semibold text-emerald-700">Đang mở cửa</span>
                </div>
                @yield('header_actions')
            </div>
        </div>
    </header>

    {{-- ── Sidebar ── --}}
    <aside class="menu-sidebar">
        @yield('sidebar')
    </aside>

    {{-- ── Main content ── --}}
    <main class="menu-main">
        @yield('content')
    </main>

    {{-- ── Cart panel ── --}}
    <aside class="menu-cart">
        @yield('cart_panel')
    </aside>

</div>

{{-- Mobile floating elements --}}
@yield('mobile_float')

@yield('scripts')
</body>
</html>
