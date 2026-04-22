@extends('layouts.admin')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Edit Category</h2>
        <p class="text-sm text-slate-500">Update details for {{ $category->name }}.</p>
    </div>
@endsection

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Restaurant</label>
                    <select name="restaurant_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}" {{ $category->restaurant_id == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Category Name</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Parent Category (Optional)</label>
                    <select name="parent_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                        <option value="">None (Top Level)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Order Index</label>
                    <input type="number" name="order_index" value="{{ $category->order_index }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Category Image</label>
                    <div class="flex items-center space-x-4">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" class="w-16 h-16 rounded-lg object-cover">
                        @endif
                        <input type="file" name="image" class="flex-1 px-4 py-2 border border-slate-200 border-dashed rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Recommended: Square image, max 2MB. Leave blank to keep current.</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-800 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-xl font-bold text-sm hover:bg-orange-700 transition-colors shadow-lg shadow-orange-200">
                Update Category
            </button>
        </div>
    </form>
</div>
@endsection
