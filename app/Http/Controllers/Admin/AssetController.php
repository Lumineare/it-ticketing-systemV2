<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    // == Daftar Kategori Aset ==
    // Tambahkan atau ubah kategori di sini, dan akan otomatis muncul di semua form.
    const CATEGORIES = [
        'Laptop', 'Desktop/PC', 'Printer', 'Monitor',
        'Switch/Router', 'UPS', 'Scanner', 'Proyektor', 'Kamera', 'Lainnya',
    ];

    // == Daftar Kondisi ==
    // Ubah label kondisi di sini jika diperlukan.
    const CONDITIONS = ['Baik', 'Perlu Servis', 'Rusak', 'Tidak Aktif'];

    /**
     * Tampilkan daftar semua aset dengan fitur pencarian dan filter.
     */
    public function index(Request $request)
    {
        $query = Asset::query();

        // Pencarian berdasarkan nama, brand, serial number, atau lokasi
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('asset_id', 'like', "%$search%")
                  ->orWhere('brand', 'like', "%$search%")
                  ->orWhere('serial_number', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%")
                  ->orWhere('assigned_to', 'like', "%$search%");
            });
        }

        // Filter berdasarkan kategori
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // Filter berdasarkan kondisi
        if ($condition = $request->input('condition')) {
            $query->where('condition', $condition);
        }

        $assets = $query->latest()->paginate(15)->withQueryString();
        $categories = self::CATEGORIES;
        $conditions = self::CONDITIONS;

        return view('admin.assets.index', compact('assets', 'categories', 'conditions'));
    }

    /**
     * Tampilkan form untuk menambah aset baru.
     */
    public function create()
    {
        $categories = self::CATEGORIES;
        $conditions = self::CONDITIONS;
        return view('admin.assets.create', compact('categories', 'conditions'));
    }

    /**
     * Simpan aset baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'category'        => 'required|string',
            'brand'           => 'nullable|string|max:100',
            'model'           => 'nullable|string|max:100',
            'serial_number'   => 'nullable|string|max:100|unique:assets,serial_number',
            'condition'       => 'required|string',
            'location'        => 'nullable|string|max:100',
            'assigned_to'     => 'nullable|string|max:100',
            'purchase_date'   => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'purchase_price'  => 'nullable|numeric|min:0',
            'specs'           => 'nullable|string',
            'notes'           => 'nullable|string',
        ]);

        // -- Logika Generate Asset ID otomatis (format: AST-YYYYMMDD-0001) --
        $date = date('Ymd');
        $lastAsset = Asset::whereDate('created_at', today())->latest('id')->first();
        $sequence = $lastAsset ? ((int) substr($lastAsset->asset_id, -4)) + 1 : 1;
        $validated['asset_id'] = 'AST-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        Asset::create($validated);

        return redirect()->route('admin.assets.index')
            ->with('success', 'Aset berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail satu aset.
     */
    public function show(Asset $asset)
    {
        return view('admin.assets.show', compact('asset'));
    }

    /**
     * Tampilkan form edit aset.
     */
    public function edit(Asset $asset)
    {
        $categories = self::CATEGORIES;
        $conditions = self::CONDITIONS;
        return view('admin.assets.edit', compact('asset', 'categories', 'conditions'));
    }

    /**
     * Update data aset di database.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'category'        => 'required|string',
            'brand'           => 'nullable|string|max:100',
            'model'           => 'nullable|string|max:100',
            'serial_number'   => 'nullable|string|max:100|unique:assets,serial_number,' . $asset->id,
            'condition'       => 'required|string',
            'location'        => 'nullable|string|max:100',
            'assigned_to'     => 'nullable|string|max:100',
            'purchase_date'   => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'purchase_price'  => 'nullable|numeric|min:0',
            'specs'           => 'nullable|string',
            'notes'           => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('admin.assets.show', $asset)
            ->with('success', 'Aset berhasil diperbarui!');
    }

    /**
     * Hapus aset dari database.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('admin.assets.index')
            ->with('success', 'Aset berhasil dihapus.');
    }
}
