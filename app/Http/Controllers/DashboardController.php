<?php

namespace App\Http\Controllers;

use App\Models\asset as AssetModel;
use App\Models\MasterAsset;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalMasterAssets = MasterAsset::count();
        $activeMasterAssets = MasterAsset::where('status', 1)->count();
        $totalAssets = AssetModel::count();
        $trackedAssets = DB::table('asset_locations')
            ->distinct()
            ->count('gps_number');

        $latestAssets = AssetModel::with('masterAsset')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalMasterAssets',
            'activeMasterAssets',
            'totalAssets',
            'trackedAssets',
            'latestAssets'
        ));
    }
}
