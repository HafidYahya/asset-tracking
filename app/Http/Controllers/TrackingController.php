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

        $latestLocationSubquery = DB::table('locations')
            ->select('imei', DB::raw('MAX(id) as max_id'))
            ->groupBy('imei');

        $trackings = DB::table('locations as al')
            ->joinSub($latestLocationSubquery, 'latest', function ($join) {
                $join->on('al.id', '=', 'latest.max_id');
            })
            ->leftJoin('assets as a', 'a.gps_number', '=', 'al.imei')
            ->leftJoin('master_assets as ma', 'ma.id', '=', 'a.master_asset_id')
            ->select([
                'al.imei as imei',
                'al.status',
                'al.lat',
                'al.lng',
                'al.electQuantity',
                'a.asset_code',
                'ma.asset_name',
            ])
            ->orderBy('al.imei')
            ->paginate($perPage)
            ->withQueryString();

        return view('tracking.index', compact('trackings'));
    }
}