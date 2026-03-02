<?php

namespace App\Http\Controllers;

use App\Models\MasterAsset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterAssetController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        // 1. Ambil record terakhir
        $latestAsset = MasterAsset::latest('id')->first();

        if (!$latestAsset) {
            // Jika belum ada data sama sekali
            $nextCode = 'MS-001';
        } else {
            // 2. Ambil angka dari kode terakhir (contoh: MS-005 -> 005)
            $lastNumber = (int) substr($latestAsset->asset_code, 3);

            // 3. Tambah 1 dan beri padding 3 digit (contoh: 5 jadi 006)
            $nextCode = 'MS-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        $query = MasterAsset::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('asset_name', 'like', '%' . $request->search . '%')
                    ->orWhere('asset_code', 'like', '%' . $request->search . '%');
            });
        }

        $masterAssets = $query->latest()->paginate($perPage)->withQueryString();

        return view('master-assets.index', compact('masterAssets', 'nextCode'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'specification' => $request->input('specification', $request->input('specifications')),
            'useful_life' => $request->input('useful_life', $request->input('usefull_life')),
        ]);

        $validated = $request->validate([
            'asset_code' => ['required', 'string', 'max:50', 'unique:master_assets,asset_code'],
            'asset_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'specification' => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'useful_life' => ['required', 'integer', 'min:0'],
        ]);

        MasterAsset::create([
            'asset_code' => $validated['asset_code'],
            'asset_name' => $validated['asset_name'],
            'category' => $validated['category'] ?? null,
            'brand' => $validated['brand'] ?? null,
            'model' => $validated['model'] ?? null,
            'specifications' => $validated['specification'] ?? null,
            'purchase_price' => $validated['purchase_price'],
            'useful_life' => $validated['useful_life'] ?? null,
        ]);

        return redirect()
            ->route('master-assets.index')
            ->with('success', 'Master asset berhasil ditambahkan.');
    }

    public function update(Request $request, MasterAsset $master_asset)
    {
        $request->merge([
            'specification' => $request->input('specification', $request->input('specifications')),
            'useful_life' => $request->input('useful_life', $request->input('usefull_life')),
        ]);

        $validated = $request->validate([
            'asset_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('master_assets', 'asset_code')->ignore($master_asset->id),
            ],
            'asset_name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'specification' => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'useful_life' => ['nullable', 'integer', 'min:0'],
        ]);

        $master_asset->update([
            'asset_code' => $validated['asset_code'],
            'asset_name' => $validated['asset_name'],
            'category' => $validated['category'] ?? null,
            'brand' => $validated['brand'] ?? null,
            'model' => $validated['model'] ?? null,
            'specifications' => $validated['specification'] ?? null,
            'purchase_price' => $validated['purchase_price'],
            'useful_life' => $validated['useful_life'] ?? null,
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('master-assets.index')
            ->with('success', 'Master asset berhasil diperbarui.');
    }

    public function destroy(MasterAsset $master_asset)
    {
        $master_asset->delete();

        return redirect()
            ->route('master-assets.index')
            ->with('success', 'Master asset berhasil dihapus.');
    }

    public function changeStatus(Request $request, MasterAsset $master_asset)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:0,1'],
        ]);

        $master_asset->update([
            'status' => (int) $validated['status'],
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Status master asset berhasil diperbarui.');
    }
}
