<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Khóa Bảo Mật - QR Menu SaaS</title>
    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; overflow: hidden; }
        .glass-panel { background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .text-gradient { background: linear-gradient(135deg, #f97316 0%, #fbbf24 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="h-screen w-full flex">
    
    <!-- Left Pattern/Image Area (Hidden on Mobile) -->
    <div class="hidden lg:flex w-1/2 relative bg-slate-900 justify-center items-center overflow-hidden">
        <!-- Abstract gradient shapes -->
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-orange-600/20 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-amber-500/20 blur-[120px] rounded-full"></div>
        
        <div class="relative z-10 glass-panel p-12 rounded-[3rem] border border-orange-500/10 shadow-[0_0_100px_rgba(249,115,22,0.1)] max-w-lg text-center transform hover:scale-105 transition-transform duration-700">
            <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-amber-500 rounded-2xl flex items-center justify-center text-white mx-auto mb-8 shadow-lg shadow-orange-500/30">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            </div>
            <h2 class="text-4xl font-extrabold text-white mb-4 leading-tight">Chào mừng trở lại. <br>Hệ sinh thái <span class="text-gradient">Hoàn Mỹ</span>.</h2>
            <p class="text-slate-400 text-lg">Hệ thống của bạn đang được bảo vệ bằng chuẩn bảo mật cao nhất. Đăng nhập để tiếp tục quản lý dòng tiền của nhà hàng.</p>
        </div>
        
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 pointer-events-none"></div>
    </div>

    <!-- Right Login Area -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center relative bg-slate-900 border-l border-slate-800">
        <!-- Floating shape for mobile -->
        <div class="absolute lg:hidden top-0 left-0 w-[300px] h-[300px] bg-orange-600/20 blur-[100px] rounded-full"></div>
        
        <div class="w-full max-w-md px-8 relative z-10">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center justify-center mb-10">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-500/30 mr-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-white">QRMenu.</span>
            </div>

            <div class="text-left mb-10">
                <h1 class="text-3xl font-bold text-white mb-2">Đăng Nhập</h1>
                <p class="text-slate-400">Truy cập vào hệ thống Quản trị & Vận hành.</p>
            </div>

            <!-- Session Status & Errors -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-400 bg-green-400/10 p-3 rounded-xl border border-green-400/20">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/20 p-4 rounded-xl">
                    <div class="font-medium text-red-500 mb-1">Rất tiếc! Đã có lỗi xảy ra.</div>
                    <ul class="text-sm text-red-400 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Địa chỉ Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="block w-full pl-11 pr-4 py-3.5 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-medium text-slate-300">Mật khẩu</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-orange-400 hover:text-orange-300 hover:underline transition-colors">
                                Quên mật khẩu?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="block w-full pl-11 pr-4 py-3.5 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-orange-500 focus:ring-orange-500 focus:ring-offset-slate-900 border-gray-300">
                    <label for="remember_me" class="ml-2 block text-sm text-slate-400">
                        Ghi nhớ thiết bị này
                    </label>
                </div>

                <!-- Setup Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-[0_0_20px_rgba(249,115,22,0.3)] text-base font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:scale-[1.02] hover:shadow-[0_0_30px_rgba(249,115,22,0.5)] transition-all duration-300">
                        Đăng nhập ngay
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
            
            <p class="mt-8 text-center text-sm text-slate-500">
                Chưa có nhà hàng? <a href="{{ route('register') }}" class="font-bold text-white hover:text-orange-400 transition-colors">Yêu cầu mở mới</a>
            </p>
        </div>
    </div>
</body>
</html>
