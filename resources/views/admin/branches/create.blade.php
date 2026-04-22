@extends('layouts.admin')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Add New Branch</h2>
        <p class="text-sm text-slate-500">Create a new location for your restaurant.</p>
    </div>
@endsection

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.branches.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Restaurant</label>
                    <select name="restaurant_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Branch Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" placeholder="e.g. Downtown Central" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Address</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" placeholder="Physical address..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Phone</label>
                    <input type="text" name="phone" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Branch Manager</label>
                    <select name="manager_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                        <option value="">Select Manager</option>
                        @foreach($staffUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Opening Time</label>
                    <input type="time" name="opening_time" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Closing Time</label>
                    <input type="time" name="closing_time" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active" class="w-4 h-4 text-orange-600 border-slate-300 rounded focus:ring-orange-500" checked>
                        <label for="is_active" class="ml-2 block text-sm font-medium text-slate-700">Active and open for business</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.branches.index') }}" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-800 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors shadow-lg">
                Create Branch
            </button>
        </div>
    </form>
</div>
@endsection
