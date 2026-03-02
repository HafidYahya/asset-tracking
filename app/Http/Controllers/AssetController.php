<?php

namespace App\Http\Controllers;

use App\Models\asset as AssetModel;
use App\Models\MasterAsset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetModel::query()->with('masterAsset');

        $assets = $query->latest()->get();
        $masterAssets = MasterAsset::where('status', 1)->orderBy('asset_name')->get(['id', 'asset_code', 'asset_name']);
        $nextAssetCode = $this->generateNextAssetCode();

        return view('assets.index', compact('assets', 'masterAssets', 'nextAssetCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'master_asset_id' => ['required', 'exists:master_assets,id'],
            'gps_number' => ['nullable', 'digits_between:10,20', 'unique:assets,gps_number'],
            'condition' => ['required', Rule::in(['good', 'minor', 'major', 'broken'])],
            'purchase_date' => ['required', 'date'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'warranty_expired' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['asset_code'] = $this->generateNextAssetCode();
        AssetModel::create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset berhasil ditambahkan.');
    }

    public function update(Request $request, AssetModel $asset)
    {
        $validated = $request->validate([
            'master_asset_id' => ['required', 'exists:master_assets,id'],
            'gps_number' => ['nullable', 'digits_between:10,20', Rule::unique('assets', 'gps_number')->ignore($asset->id)],
            'condition' => ['required', Rule::in(['good', 'minor', 'major', 'broken'])],
            'purchase_date' => ['required', 'date'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'warranty_expired' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset berhasil diperbarui.');
    }

    public function destroy(AssetModel $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset berhasil dihapus.');
    }

    private function generateNextAssetCode(): string
    {
        $lastCode = AssetModel::whereNotNull('asset_code')
            ->orderByDesc('id')
            ->value('asset_code');

        if (!$lastCode || !preg_match('/^KA(\d+)$/', $lastCode, $matches)) {
            return 'KA001';
        }

        $nextNumber = (int) $matches[1] + 1;
        return 'KA' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
