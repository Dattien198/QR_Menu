@extends('layouts.admin')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Edit Menu Item</h2>
        <p class="text-sm text-slate-500">Update details for {{ $menuItem->name }}.</p>
    </div>
@endsection

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.menu-items.update', $menuItem) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Item Name</label>
                    <input type="text" name="name" value="{{ $menuItem->name }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Category</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $menuItem->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Price (VND)</label>
                    <input type="number" name="price" value="{{ $menuItem->price }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">{{ $menuItem->description }}</textarea>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Item Image</label>
                    <div class="flex items-center space-x-4">
                        @if($menuItem->image)
                            <img src="{{ Storage::url($menuItem->image) }}" class="w-16 h-16 rounded-lg object-cover">
                        @endif
                        <input type="file" name="image" class="flex-1 px-4 py-2 border border-slate-200 border-dashed rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                    </div>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Display Order</label>
                    <input type="number" name="order_index" value="{{ $menuItem->order_index }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="hidden" name="is_available" value="0">
                        <input type="checkbox" name="is_available" value="1" id="is_available" class="w-4 h-4 text-orange-600 border-slate-300 rounded focus:ring-orange-500" {{ $menuItem->is_available ? 'checked' : '' }}>
                        <label for="is_available" class="ml-2 block text-sm font-medium text-slate-700">Available for ordering</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.menu-items.index') }}" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-800 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-xl font-bold text-sm hover:bg-orange-700 transition-colors shadow-lg shadow-orange-200">
                Update Menu Item
            </button>
        </div>
    </form>
</div>
@endsection
