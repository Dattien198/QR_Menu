@extends('layouts.admin')

@section('header', 'New Restaurant')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.restaurants.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-orange-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-900">Add New Restaurant</h2>
            <p class="text-xs text-slate-500 text-slate-500">Enter the core details for your new restaurant establishment.</p>
        </div>

        <form action="{{ route('admin.restaurants.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="col-span-1">
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Restaurant Name</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}" 
                        x-data @input="$dispatch('slug-update', { name: $el.value })"
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Slug -->
                <div class="col-span-1" x-data="{ slug: '' }" @slug-update.window="slug = $event.detail.name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')">
                    <label for="slug" class="block text-sm font-semibold text-slate-700 mb-1">Slug (URL)</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 text-slate-500 text-sm">/menu/</span>
                        <input type="text" name="slug" id="slug" required :value="slug" readonly
                            class="flex-1 px-4 py-2 bg-slate-100 border border-slate-200 rounded-r-xl text-slate-500 cursor-not-allowed">
                    </div>
                    @error('slug')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1">Address</label>
                <textarea name="address" id="address" rows="3" 
                    class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">{{ old('address') }}</textarea>
                @error('address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Contact -->
                <div>
                    <label for="contact" class="block text-sm font-semibold text-slate-700 mb-1">Contact Phone</label>
                    <input type="text" name="contact" id="contact" value="{{ old('contact') }}" 
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    @error('contact')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Currency -->
                <div>
                    <label for="currency" class="block text-sm font-semibold text-slate-700 mb-1">Currency</label>
                    <select name="currency" id="currency" 
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="VND" selected>VND</option>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                    </select>
                </div>

                <!-- Theme Color -->
                <div>
                    <label for="theme_color" class="block text-sm font-semibold text-slate-700 mb-1">Theme Color</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="theme_color" id="theme_color" value="#f97316" 
                            class="h-10 w-12 p-1 bg-white border border-slate-200 rounded-xl cursor-pointer">
                        <span class="text-xs text-slate-500">Pick a brand color</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-slate-100 mt-6 mt-6">
                <a href="{{ route('admin.restaurants.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 shadow-lg shadow-orange-200 transition-all">
                    Create Restaurant
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
