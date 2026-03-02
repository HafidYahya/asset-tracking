<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $latestLocationSubquery = DB::table('asset_locations')
            ->select('gps_number', DB::raw('MAX(id) as max_id'))
            ->groupBy('gps_number');

        $trackings = DB::table('asset_locations as al')
            ->joinSub($latestLocationSubquery, 'latest', function ($join) {
                $join->on('al.id', '=', 'latest.max_id');
            })
            ->leftJoin('assets as a', 'a.gps_number', '=', 'al.gps_number')
            ->leftJoin('master_assets as ma', 'ma.id', '=', 'a.master_asset_id')
            ->select([
                'al.gps_number as imei',
                'al.latitude',
                'al.longitude',
                'al.electricity',
                'a.asset_code',
                'ma.asset_name',
            ])
            ->orderBy('al.gps_number')
            ->paginate($perPage)
            ->withQueryString();

        return view('tracking.index', compact('trackings'));
    }
}
