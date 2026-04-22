<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with(['restaurant', 'manager'])->latest()->paginate(15);
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $restaurants = Restaurant::all();
        $staffUsers = User::role(['admin', 'manager', 'cashier'])->get();
        return view('admin.branches.create', compact('restaurants', 'staffUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'manager_id' => 'nullable|exists:users,id',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'is_active' => 'boolean',
        ]);

        Branch::create($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        $restaurants = Restaurant::all();
        $staffUsers = User::role(['admin', 'manager', 'cashier'])->get();
        return view('admin.branches.edit', compact('branch', 'restaurants', 'staffUsers'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'manager_id' => 'nullable|exists:users,id',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully.');
    }
}
