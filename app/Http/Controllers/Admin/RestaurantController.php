<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Restaurant;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::withCount('branches')->latest()->paginate(10);
        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('admin.restaurants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:restaurants,slug',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'address' => 'nullable|string',
            'currency' => 'required|string|max:10',
            'theme_color' => 'nullable|string|max:20',
        ]);

        $restaurant = Restaurant::create($validated);

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant created successfully.');
    }

    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:restaurants,slug,' . $restaurant->id,
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'address' => 'nullable|string',
            'currency' => 'required|string|max:10',
            'theme_color' => 'nullable|string|max:20',
        ]);

        $restaurant->update($validated);

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant updated successfully.');
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant deleted successfully.');
    }
}
