<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kitchen Display System (KDS)</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <style>
        body { font-family: 'Roboto Mono', monospace; }
        [x-cloak] { display: none !important; }
        .kds-card-emerge { animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes popIn { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-gray-300 h-screen overflow-hidden flex flex-col">

    <!-- Header -->
    <header class="bg-gray-900 border-b border-gray-800 px-6 py-4 flex items-center justify-between shrink-0">
        <div class="flex items-center space-x-6">
            <h1 class="text-2xl font-bold text-white tracking-widest"><span class="text-orange-500">QR</span> BẾP</h1>
            <div class="flex space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-800 text-gray-300 border border-gray-700">Tổng: {{ $orders->count() }}</span>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-400" id="clock">00:00:00</div>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-800 text-gray-300 hover:text-white rounded-lg text-sm font-semibold transition-colors border border-gray-700">Thoát Bếp</a>
        </div>
    </header>

    <!-- Main Grid -->
    <main class="flex-1 overflow-x-auto overflow-y-hidden p-6" x-data="kdsState({{ $orders->toJson() }})">
        <div class="flex gap-6 h-full items-start">
            
            <template x-for="order in orders" :key="order.id">
                <div x-show="!isOrderDone(order)" x-transition.duration.300ms class="kds-card-emerge flex-shrink-0 w-80 h-full flex flex-col bg-gray-900 rounded-xl border border-gray-800 shadow-[0_8px_30px_rgb(0,0,0,0.5)] overflow-hidden"
                     :class="getWaitTimeClass(order.created_at)">
                    
                    <!-- Card Header -->
                    <div class="px-5 py-4 border-b border-gray-800" :class="getWaitTimeHeaderClass(order.created_at)">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-4xl font-bold text-white leading-none" x-text="order.table.name"></div>
                                <div class="text-xs font-bold text-gray-400 mt-2 tracking-widest">ĐƠN HÀNG #<span x-text="order.order_code"></span></div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-300" x-text="getWaitTime(order.created_at)"></div>
                            </div>
                        </div>
                        <template x-if="order.note">
                            <div class="mt-3 p-2 bg-yellow-500/10 border border-yellow-500/20 rounded-md text-yellow-500 text-xs font-bold">
                                ⚠️ <span x-text="order.note"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Items List -->
                    <div class="flex-1 overflow-y-auto p-2 custom-scrollbar">
                        <div class="space-y-2">
                            <template x-for="item in order.items" :key="item.id">
                                <button @click="toggleStatus(item)" 
                                        class="w-full text-left p-3 rounded-lg border transition-all active:scale-95 flex gap-3 group"
                                        :class="{
                                            'bg-gray-800 border-gray-700 hover:border-orange-500': item.status === 'pending',
                                            'bg-orange-500/20 border-orange-500 text-orange-400': item.status === 'preparing',
                                            'bg-green-500/10 border-green-500 text-green-500 opacity-50': item.status === 'ready' || item.status === 'served'
                                        }"
                                        :disabled="item.status === 'ready' || item.status === 'served'">
                                    
                                    <div class="text-lg font-bold w-6 text-center" x-text="item.quantity"></div>
                                    <div class="flex-1">
                                        <div class="font-bold text-sm" :class="item.status === 'ready' ? 'line-through' : 'text-gray-200'" x-text="item.menu_item.name"></div>
                                        <template x-if="item.note">
                                            <div class="text-xs mt-1 text-red-400 font-semibold uppercase">** <span x-text="item.note"></span></div>
                                        </template>
                                    </div>
                                    <div class="w-2 rounded-full" :class="{
                                        'bg-gray-600': item.status === 'pending',
                                        'bg-orange-500 animate-pulse': item.status === 'preparing',
                                        'bg-green-500': item.status === 'ready' || item.status === 'served'
                                    }"></div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Complete Action -->
                    <div class="p-4 bg-gray-900 border-t border-gray-800">
                        <button @click="markOrderReady(order)" 
                                class="w-full py-3 rounded-lg font-bold text-sm tracking-widest transition-all"
                                :class="canComplete(order) ? 'bg-green-600 hover:bg-green-500 text-white shadow-[0_0_15px_rgba(22,163,74,0.5)]' : 'bg-gray-800 text-gray-500 cursor-not-allowed'"
                                :disabled="!canComplete(order)">
                            XÁC NHẬN XONG
                        </button>
                    </div>
                </div>
            </template>
            
            <div x-show="orders.filter(o => !isOrderDone(o)).length === 0" class="w-full flex items-center justify-center text-gray-600 font-bold text-2xl h-full" x-cloak>
                CHƯA CÓ ĐƠN HÀNG MỚI
            </div>
            
        </div>
    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Clock
        setInterval(() => {
            document.getElementById('clock').innerText = new Date().toLocaleTimeString('en-US', { hour12: false });
        }, 1000);

        document.addEventListener('alpine:init', () => {
            Alpine.data('kdsState', (initialOrders) => ({
                orders: initialOrders,
                now: new Date(),

                init() {
                    setInterval(() => { this.now = new Date(); }, 60000); // update times every minute
                    
                    // Listen for WebSocket (To be implemented when Reverb is setup)
                    /*
                    Echo.channel('kitchen')
                        .listen('OrderPlaced', (e) => {
                            this.orders.push(e.order);
                            // Play beep sound
                        });
                    */
                },

                getWaitTime(createdAt) {
                    const diff = Math.floor((this.now - new Date(createdAt)) / 60000);
                    return diff + 'p';
                },

                getWaitTimeClass(createdAt) {
                    const diff = Math.floor((this.now - new Date(createdAt)) / 60000);
                    if (diff > 15) return 'border-red-500/50 shadow-[0_0_15px_rgba(239,68,68,0.2)]';
                    if (diff > 10) return 'border-yellow-500/50 shadow-[0_0_15px_rgba(234,179,8,0.1)]';
                    return '';
                },
                
                getWaitTimeHeaderClass(createdAt) {
                    const diff = Math.floor((this.now - new Date(createdAt)) / 60000);
                    if (diff > 15) return 'bg-red-500/20 text-red-300';
                    if (diff > 10) return 'bg-yellow-500/20 text-yellow-300';
                    return 'bg-gray-800/50';
                },

                isOrderDone(order) {
                    return order.items.every(i => i.status === 'ready' || i.status === 'served');
                },
                
                canComplete(order) {
                    // Kitchen can manually press Complete if all items are checked OR to bulk complete
                    return true;
                },

                async toggleStatus(item) {
                    if(item.status === 'ready' || item.status === 'served') return;

                    const newStatus = item.status === 'pending' ? 'preparing' : 'ready';
                    item.status = newStatus; // Optimistic update

                    try {
                        await fetch(`/kitchen/item/${item.id}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ status: newStatus })
                        });
                    } catch (e) {
                        console.error(e);
                    }
                },

                async markOrderReady(order) {
                    // Bulk mark all unready items as ready
                    order.items.forEach(async item => {
                        if(item.status !== 'ready' && item.status !== 'served') {
                            item.status = 'ready';
                            try {
                                await fetch(`/kitchen/item/${item.id}/status`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ status: 'ready' })
                                });
                            } catch (e) {}
                        }
                    });
                }
            }));
        });
    </script>
</body>
</html>
