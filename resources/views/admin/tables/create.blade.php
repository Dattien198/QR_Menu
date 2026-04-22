@extends('layouts.admin')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Add New Tables</h2>
        <p class="text-sm text-slate-500">Create entries and generate QR codes for your tables.</p>
    </div>
@endsection

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.tables.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Branch</label>
                    <select name="branch_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->restaurant->name }} - {{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Table Name/No.</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" placeholder="e.g. Table 01" required>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Capacity</label>
                    <input type="number" name="capacity" value="2" min="1" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" required>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Floor/Level</label>
                    <input type="text" name="floor" value="1F" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Area/Zone</label>
                    <input type="text" name="area" placeholder="e.g. Terrace" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.tables.index') }}" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-800 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors shadow-lg">
                Generate Table & QR
            </button>
        </div>
    </form>
</div>
@endsection
