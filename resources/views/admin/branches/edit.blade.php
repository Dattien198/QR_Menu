@extends('layouts.admin')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Edit Branch</h2>
        <p class="text-sm text-slate-500">Update information for {{ $branch->name }}.</p>
    </div>
@endsection

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.branches.update', $branch) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Restaurant</label>
                    <select name="restaurant_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}" {{ $branch->restaurant_id == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Branch Name</label>
                    <input type="text" name="name" value="{{ $branch->name }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Address</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">{{ $branch->address }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ $branch->phone }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Branch Manager</label>
                    <select name="manager_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                        <option value="">Select Manager</option>
                        @foreach($staffUsers as $user)
                            <option value="{{ $user->id }}" {{ $branch->manager_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Opening Time</label>
                    <input type="time" name="opening_time" value="{{ $branch->opening_time }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Closing Time</label>
                    <input type="time" name="closing_time" value="{{ $branch->closing_time }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active" class="w-4 h-4 text-orange-600 border-slate-300 rounded focus:ring-orange-500" {{ $branch->is_active ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm font-medium text-slate-700">Active and open for business</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.branches.index') }}" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-800 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-xl font-bold text-sm hover:bg-orange-700 transition-colors shadow-lg shadow-orange-200">
                Update Branch
            </button>
        </div>
    </form>
</div>
@endsection
