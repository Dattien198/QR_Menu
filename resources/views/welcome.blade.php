<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Menu SaaS - Nền Tảng Chuyển Đổi Số Nhà Hàng</title>
    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine JS Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; overflow-x: hidden; }
        .glass-panel { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-nav { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .text-gradient { background: linear-gradient(135deg, #f97316 0%, #fbbf24 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bg-gradient-hero { background: radial-gradient(circle at 50% 0%, rgba(249, 115, 22, 0.15) 0%, rgba(15, 23, 42, 1) 50%); }
    </style>
</head>
<body class="bg-gradient-hero" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'glass-nav py-3' : 'bg-transparent py-5'" class="fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-white">QRMenu<span class="text-orange-500">.</span></span>
            </div>
            
            <div class="hidden md:flex items-center space-x-8 text-sm font-semibold">
                <a href="#features" class="text-slate-300 hover:text-white hover:scale-105 transition-all">Tính năng</a>
                <a href="#features" class="text-slate-300 hover:text-white hover:scale-105 transition-all">Giải pháp</a>
                @guest
                    <a href="{{ route('login') }}" class="text-slate-300 hover:text-white transition-colors">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-white text-slate-900 rounded-full hover:bg-slate-100 hover:scale-105 transition-all shadow-lg shadow-white/10 font-bold">Dùng thử miễn phí</a>
                @else
                    <a href="{{ auth()->user()->hasAnyRole(['admin', 'superadmin', 'kitchen', 'cashier']) ? route('dashboard') : '/menu/vua-pho/table-token-1' }}" class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-full hover:scale-105 transition-all shadow-lg shadow-orange-500/25 font-bold">Vào Hệ thống</a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-40 pb-20 px-6 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16 relative z-10">
            <!-- Text Content -->
            <div class="flex-1 text-center lg:text-left" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)">
                <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full glass-panel text-xs font-bold mb-6 border border-white/10"
                     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);">
                    <span class="text-orange-400">🔥 Đột phá Công nghệ Nhà hàng 2024</span>
                    <span class="w-1 h-1 bg-slate-500 rounded-full"></span>
                    <span class="text-slate-300">Nền tảng Toàn diện 3-trong-1</span>
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-extrabold text-white mb-6 leading-[1.1] tracking-tight"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s;">
                    Gọi Món Nhàn Tênh <br>
                    <span class="text-gradient block mt-2">Vận Hành Thần Tốc</span>
                </h1>
                
                <p class="max-w-2xl mx-auto lg:mx-0 text-lg lg:text-xl text-slate-400 mb-10 leading-relaxed font-medium"
                   :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;">
                    Xóa bỏ hoàn toàn thực đơn giấy và tình trạng quá tải thu ngân. Khách hàng tự gọi món bằng mã QR, bếp nhận đơn tức thì. Tối ưu 30% chi phí nhân sự và hạn chế 100% nhầm lẫn!
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4"
                     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.3s;">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-900 rounded-full font-extrabold text-lg hover:bg-slate-100 hover:scale-105 transition-all shadow-[0_0_40px_rgba(255,255,255,0.2)] flex items-center justify-center group">
                        Bắt đầu ngay hôm nay
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                    <a href="/menu/vua-pho/table-token-1" class="w-full sm:w-auto px-8 py-4 glass-panel text-white rounded-full font-bold text-lg hover:bg-white/10 transition-all flex items-center justify-center group">
                        <svg class="w-5 h-5 mr-2 text-orange-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Xem Giao diện Đặt món
                    </a>
                </div>
            </div>

            <!-- Hero Mockup Images -->
            <div class="flex-1 w-full relative" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 300)">
                <div class="relative w-full aspect-square md:aspect-[4/3] rounded-2xl"
                     :class="shown ? 'opacity-100 translate-x-0 scale-100' : 'opacity-0 translate-x-10 scale-95'" style="transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);">
                    
                    <!-- Admin Mockup (Back) -->
                    <div class="absolute top-0 right-0 w-[85%] rounded-2xl overflow-hidden shadow-2xl glass-panel p-2 shadow-orange-500/10">
                        <div class="rounded-xl overflow-hidden border border-slate-700/50">
                            <img src="{{ asset('images/dashboard-mockup.png') }}" class="w-full h-auto" alt="Admin Dashboard Mockup">
                        </div>
                    </div>
                    
                    <!-- Mobile Mockup (Front) -->
                    <div class="absolute bottom-0 left-0 w-[40%] rounded-[2rem] overflow-hidden shadow-2xl glass-panel p-2 border border-slate-700 shadow-orange-500/20 translate-y-8"
                         x-data="{ float: false }" x-init="setTimeout(() => float = true, 1000)" :class="float ? 'animate-bounce-slow' : ''">
                        <div class="rounded-[1.5rem] overflow-hidden border border-slate-800 bg-slate-900 object-cover">
                            <img src="{{ asset('images/mobile-mockup.png') }}" class="w-full h-auto" alt="Mobile App Mockup">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Bento Grid Features -->
    <section id="features" class="py-24 px-6 relative">
        <div class="absolute right-0 top-0 w-96 h-96 bg-blue-500/10 blur-[100px] rounded-full"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-16" x-data x-intersect="$el.classList.add('opacity-100', 'translate-y-0'); $el.classList.remove('opacity-0', 'translate-y-8')" class="opacity-0 translate-y-8 transition-all duration-700">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-4">Hệ sinh thái <span class="text-gradient">Toàn diện</span></h2>
                <p class="text-slate-400 text-lg max-w-2xl mx-auto">Chúng tôi không chỉ là một chiếc thực đơn điện tử. Mã QR là chìa khóa mở ra luồng vận hành khép kín hoàn hảo từ sảnh, qua bếp, thẳng đến tủ tiền.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[minmax(250px,auto)]">
                <!-- Large Feature -->
                <div class="md:col-span-2 glass-panel rounded-3xl p-8 md:p-12 group hover:bg-slate-800/60 transition-colors overflow-hidden relative border border-orange-500/20 shadow-lg shadow-orange-500/5" x-data x-intersect="$el.classList.add('opacity-100', 'translate-y-0'); $el.classList.remove('opacity-0', 'translate-y-8')" class="opacity-0 translate-y-8 transition-all duration-700 delay-100">
                    <div class="relative z-10 w-full md:w-2/3">
                        <div class="w-14 h-14 bg-orange-500/20 rounded-2xl flex items-center justify-center text-orange-400 mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-bold text-white mb-4 leading-tight">Mở camera là Đặt ngay <br>Không App, Không Tài khoản</h3>
                        <p class="text-slate-300 text-lg leading-relaxed">Loại bỏ nỗi ám ảnh "Anh ơi cho xin cuốn thực đơn" hay những cú cáu gắt vì sai món. Giao diện trực quan đẹp mắt chốt đơn nhanh như chớp. Dễ dàng với cả người lớn tuổi.</p>
                    </div>
                    <div class="absolute right-[-10%] bottom-[-20%] w-2/3 aspect-[3/4] opacity-20 bg-orange-500 blur-3xl rounded-full group-hover:opacity-40 transition-opacity"></div>
                </div>

                <!-- Box 2 -->
                <div class="glass-panel rounded-3xl p-8 group hover:bg-slate-800/60 transition-colors relative overflow-hidden" x-data x-intersect="$el.classList.add('opacity-100', 'translate-y-0'); $el.classList.remove('opacity-0', 'translate-y-8')" class="opacity-0 translate-y-8 transition-all duration-700 delay-200">
                    <div class="w-12 h-12 bg-amber-500/20 rounded-2xl flex items-center justify-center text-amber-400 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Màn hình Tương tác Bếp</h3>
                    <p class="text-slate-400 leading-relaxed">Tuyệt đối không còn cảnh đầu bếp cãi nhau với thu ngân qua khe cửa. Món mới nhảy Notification reng reng, đánh dấu "Hoàn Thành" gửi tín hiệu lên nhà trên.</p>
                </div>

                <!-- Box 3 -->
                <div class="glass-panel rounded-3xl p-8 group hover:bg-slate-800/60 transition-colors relative overflow-hidden" x-data x-intersect="$el.classList.add('opacity-100', 'translate-y-0'); $el.classList.remove('opacity-0', 'translate-y-8')" class="opacity-0 translate-y-8 transition-all duration-700 delay-100">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Chặn Đứng Thất Thoát</h3>
                    <p class="text-slate-400 leading-relaxed">Dashboard Doanh thu minh bạch đến từng VND thời gian thực. Toàn bộ dòng tiền, lịch sử gọi thêm đều bị ghi nhận không thể sửa đổi.</p>
                </div>

                <!-- Box 4 -->
                <div class="md:col-span-2 glass-panel rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center gap-8 group hover:bg-slate-800/60 transition-colors" x-data x-intersect="$el.classList.add('opacity-100', 'translate-y-0'); $el.classList.remove('opacity-0', 'translate-y-8')" class="opacity-0 translate-y-8 transition-all duration-700 delay-200">
                    <div class="flex-1">
                        <div class="w-14 h-14 bg-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-400 mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-bold text-white mb-4">Màn hình Thu Ngân Đa nhiệm</h3>
                        <p class="text-slate-300 text-lg leading-relaxed">Một cái lướt mắt biết bàn nào đang đợi, bàn nào đang ăn, bàn nào chờ tính tiền. Click 1 điểm chuyển trạng thái "Đã thanh toán", giải phóng bàn ngay lập tức!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof / CTA -->
    <section class="py-32 px-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900 to-transparent z-0"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        
        <div class="max-w-4xl mx-auto glass-panel border border-orange-500/30 rounded-[3rem] p-12 md:p-20 text-center relative z-10 shadow-[0_0_100px_rgba(249,115,22,0.15)]" x-data x-intersect="$el.classList.add('opacity-100', 'scale-100'); $el.classList.remove('opacity-0', 'scale-95')" class="opacity-0 scale-95 transition-all duration-1000">
            <div class="inline-block px-4 py-2 bg-orange-500/10 text-orange-400 font-bold text-sm rounded-full mb-6 border border-orange-500/20">🚀 CÁCH MẠNG F&B 2026</div>
            <h2 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight leading-tight">Thời đại công nghệ <br>Không ai muốn chờ đợi.</h2>
            <p class="text-slate-400 text-lg md:text-xl mb-12 max-w-2xl mx-auto">Tham gia cùng các thương hiệu lớn đang ứng dụng QR Menu SaaS để tối ưu toàn bộ quy trình, làm hài lòng khách hàng 10/10.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-12 py-5 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-full font-bold text-lg hover:scale-105 hover:shadow-[0_0_30px_rgba(249,115,22,0.4)] transition-all flex items-center justify-center">
                    Bắt đầu với bản Quyền Lợi Kép
                </a>
            </div>
            <p class="mt-6 text-sm text-slate-500 font-medium">✨ Dùng thử đầy đủ chức năng quản trị, chưa cần nạp tiền.</p>
        </div>
    </section>

    <footer class="py-12 border-t border-slate-800 text-center text-slate-500 text-sm">
        <p class="font-medium">© 2026 QR Menu SaaS Platform. Biến đổi Không Tưởng.</p>
    </footer>

    <style>
        .animate-bounce-slow {
            animation: bounce-slow 4s ease-in-out infinite;
        }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
    </style>
</body>
</html>
