<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with('category')->latest()->paginate(15);
        return view('admin.menu-items.index', compact('menuItems'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.menu-items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:available,out_of_stock,upcoming',
            'is_featured' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $images = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('menu_items', 'public');
            }
            $validated['images'] = json_encode($images);
        }

        MenuItem::create($validated);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu Item created successfully.');
    }

    public function edit(MenuItem $menuItem)
    {
        $categories = Category::all();
        return view('admin.menu-items.edit', compact('menuItem', 'categories'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:available,out_of_stock,upcoming',
            'is_featured' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        
        if ($request->hasFile('images')) {
            // Delete old images
            if ($menuItem->images) {
                $oldImages = json_decode($menuItem->images, true);
                if (is_array($oldImages)) {
                    foreach ($oldImages as $img) {
                        Storage::disk('public')->delete($img);
                    }
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('menu_items', 'public');
            }
            $validated['images'] = json_encode($images);
        }

        $menuItem->update($validated);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu Item updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->images) {
            $images = json_decode($menuItem->images, true);
            if (is_array($images)) {
                foreach ($images as $img) {
                    Storage::disk('public')->delete($img);
                }
            }
        }
        $menuItem->delete();
        return redirect()->route('admin.menu-items.index')->with('success', 'Menu Item deleted successfully.');
    }
}
