@extends('layouts.menu')

@section('title', 'Chỉnh sửa Thông tin Cá nhân')
@section('restaurant_name', 'Tài khoản của tôi')
@section('table_name', auth()->user()->name)

@section('logo')
    <div class="w-full h-full flex items-center justify-center bg-orange-100 text-orange-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
    </div>
@endsection

@section('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 2rem;
    }
</style>
@endsection

@section('content')
<div class="px-4 py-8 max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ url()->previous() == url()->current() ? '/' : url()->previous() }}" class="inline-flex items-center text-slate-500 font-bold text-sm mb-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Quay lại
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Thông tin <span class="text-orange-600">tài khoản</span></h1>
        <p class="text-slate-500 font-medium mt-1">Cập nhật thông tin để nhận ưu đãi tốt hơn từ nhà hàng.</p>
    </div>

    <div class="glass-card p-6 shadow-xl shadow-orange-100/20">
        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Họ và Tên</label>
                <input id="name" name="name" type="text" 
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-orange-500 focus:outline-none transition-all font-bold text-slate-700" 
                    value="{{ old('name', $user->name) }}" required autofocus>
                @if($errors->get('name'))
                    <p class="mt-2 text-sm text-red-500 font-medium ml-1">{{ $errors->get('name')[0] }}</p>
                @endif
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                <input id="email" name="email" type="email" 
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-orange-500 focus:outline-none transition-all font-bold text-slate-700" 
                    value="{{ old('email', $user->email) }}" required>
                @if($errors->get('email'))
                    <p class="mt-2 text-sm text-red-500 font-medium ml-1">{{ $errors->get('email')[0] }}</p>
                @endif
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Số điện thoại</label>
                <input id="phone" name="phone" type="text" 
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-orange-500 focus:outline-none transition-all font-bold text-slate-700" 
                    placeholder="09xx xxx xxx"
                    value="{{ old('phone', $user->phone) }}">
                @if($errors->get('phone'))
                    <p class="mt-2 text-sm text-red-500 font-medium ml-1">{{ $errors->get('phone')[0] }}</p>
                @endif
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-orange-600 text-white font-extrabold rounded-2xl shadow-lg shadow-orange-200 hover:bg-orange-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Lưu thay đổi
                </button>
                
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                        class="text-center mt-4 text-sm text-green-600 font-bold">
                        Đã cập nhật thông tin thành công!
                    </p>
                @endif
            </div>
        </form>
    </div>

    <!-- Security Note -->
    <div class="mt-12 p-6 bg-slate-900 rounded-3xl text-white">
        <h4 class="font-bold flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04 Pel m4.618-4.016a11.955 11.955 0 01-8.618 3.04M12 21.75l-4.243-4.243a1.998 1.998 0 010-2.827L12 10.438l4.243 4.243a1.998 1.998 0 010 2.827L12 21.75z"></path></svg>
            Bảo mật thông tin
        </h4>
        <p class="text-xs text-slate-400 leading-relaxed font-medium">Chúng tôi cam kết bảo mật thông tin cá nhân của bạn. Thông tin này chỉ được sử dụng để tối ưu hóa dịch vụ tại nhà hàng.</p>
    </div>
</div>
@endsection
