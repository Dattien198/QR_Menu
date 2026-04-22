@extends('layouts.admin')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Edit Restaurant</h2>
        <p class="text-sm text-slate-500">Modify settings for {{ $restaurant->name }}.</p>
    </div>
@endsection

@section('content')
<div class="max-w-4xl" x-data="{ name: '{{ $restaurant->name }}', slug: '{{ $restaurant->slug }}' }">
    <form action="{{ route('admin.restaurants.update', $restaurant) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Restaurant Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Restaurant Name</label>
                    <input type="text" name="name" x-model="name" 
                        @input="slug = name.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-')"
                        class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                </div>

                <!-- Slug -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">URL Slug</label>
                    <div class="flex items-center">
                        <span class="px-3 py-2 bg-slate-50 border border-r-0 border-slate-200 rounded-l-xl text-slate-500 text-sm">/menu/</span>
                        <input type="text" name="slug" x-model="slug" class="flex-1 px-4 py-2 border border-slate-200 rounded-r-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                    </div>
                </div>

                <!-- Contact & Address -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Contact Phone</label>
                    <input type="text" name="contact_phone" value="{{ $restaurant->contact_phone }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ $restaurant->contact_email }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Address</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">{{ $restaurant->address }}</textarea>
                </div>

                <!-- Regional Settings -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Currency</label>
                    <input type="text" name="currency" value="{{ $restaurant->currency }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Theme Color</label>
                    <div class="flex space-x-2">
                        <input type="color" name="theme_color" value="{{ $restaurant->theme_color }}" class="h-10 w-20 p-1 border border-slate-200 rounded-xl cursor-pointer">
                        <input type="text" value="{{ $restaurant->theme_color }}" class="flex-1 px-4 py-2 border border-slate-200 rounded-xl bg-slate-50 text-slate-500 text-sm" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.restaurants.index') }}" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-800 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors shadow-lg">
                Update Restaurant
            </button>
        </div>
    </form>
</div>
@endsection
