<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RestaurantTable;
use App\Models\Branch;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::with('branch.restaurant')->latest()->paginate(15);
        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        $branches = Branch::with('restaurant')->get();
        return view('admin.tables.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name'      => 'required|string|max:50',
            'capacity'  => 'required|integer|min:1',
            'floor'     => 'nullable|string|max:50',
            'area'      => 'nullable|string|max:50',
        ]);

        $table = RestaurantTable::create(array_merge($validated, [
            'qr_token' => Str::random(24),
            'status'   => 'empty',
        ]));

        // Generate QR using the model logic
        $table->generateQrCode();

        return redirect()->route('admin.tables.index')->with('success', 'Bàn và mã QR đã được tạo thành công.');
    }

    /**
     * Batch generate missing QR codes for all tables.
     */
    public function generateAll()
    {
        $tables = RestaurantTable::all();
        $count = 0;
        foreach ($tables as $table) {
            $table->generateQrCode();
            $count++;
        }

        return redirect()->route('admin.tables.index')->with('success', "Đã tạo/cập nhật mã QR cho $count bàn.");
    }

    public function show(RestaurantTable $table)
    {
        return view('admin.tables.show', compact('table'));
    }

    /**
     * Serve the QR code directly from storage to bypass potential symlink issues.
     */
    public function showQrCode($token)
    {
        $path = "qrcodes/table_$token.svg";
        
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $file = \Illuminate\Support\Facades\Storage::disk('public')->get($path);
        
        return response($file, 200)->header('Content-Type', 'image/svg+xml');
    }

    public function destroy(RestaurantTable $table)
    {
        if ($table->qr_path) {
            Storage::disk('public')->delete($table->qr_path);
        }
        $table->delete();
        return redirect()->route('admin.tables.index')->with('success', 'Table deleted successfully.');
    }
}
