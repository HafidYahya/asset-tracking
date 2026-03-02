<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AssetAndLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $target = 100;
        $now = now();

        $areas = [
            ['lat' => -6.2088, 'lng' => 106.8456, 'radius' => 0.03], // Jakarta Pusat
            ['lat' => -6.1745, 'lng' => 106.8227, 'radius' => 0.04], // Jakarta Utara
            ['lat' => -6.2615, 'lng' => 106.8106, 'radius' => 0.04], // Jakarta Selatan
            ['lat' => -6.2250, 'lng' => 106.9004, 'radius' => 0.04], // Jakarta Timur
            ['lat' => -6.1683, 'lng' => 106.7588, 'radius' => 0.04], // Jakarta Barat
            ['lat' => -6.4025, 'lng' => 106.7942, 'radius' => 0.05], // Depok
            ['lat' => -6.1783, 'lng' => 106.6319, 'radius' => 0.05], // Tangerang
            ['lat' => -6.2886, 'lng' => 106.7176, 'radius' => 0.05], // Tangsel
            ['lat' => -6.2383, 'lng' => 106.9756, 'radius' => 0.05], // Bekasi
            ['lat' => -6.5950, 'lng' => 106.8167, 'radius' => 0.06], // Bogor
        ];

        $existing = DB::table('assets')
            ->whereNotNull('gps_number')
            ->pluck('gps_number')
            ->merge(
                DB::table('asset_locations')
                    ->whereNotNull('gps_number')
                    ->pluck('gps_number')
            )
            ->filter()
            ->unique()
            ->flip();

        $masterId = DB::table('master_assets')->orderBy('id')->value('id');
        if (!$masterId) {
            $masterData = [
                'asset_code' => 'MS-001',
                'asset_name' => 'Auto Master Asset',
                'category' => 'GPS Device',
                'brand' => 'Generic',
                'model' => 'Tracker X',
                'specifications' => '<p>Auto generated</p>',
                'purchase_price' => 1500000,
                'useful_life' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (Schema::hasColumn('master_assets', 'status')) {
                $masterData['status'] = 1;
            }

            DB::table('master_assets')->insert($masterData);
            $masterId = DB::table('master_assets')->orderByDesc('id')->value('id');
        }

        $hasAssetCode = Schema::hasColumn('assets', 'asset_code');
        $nextNo = 1;
        if ($hasAssetCode) {
            $lastCode = DB::table('assets')->whereNotNull('asset_code')->orderByDesc('id')->value('asset_code');
            if ($lastCode && preg_match('/^KA(\d+)$/', $lastCode, $m)) {
                $nextNo = ((int) $m[1]) + 1;
            }
        }

        $imeis = [];
        $base = (int) substr((string) time(), -8);
        for ($i = 0; $i < 10000 && count($imeis) < $target; $i++) {
            $candidate = '8999' . str_pad((string) ($base * 1000 + $i), 10, '0', STR_PAD_LEFT);
            if (!$existing->has($candidate)) {
                $existing->put($candidate, true);
                $imeis[] = $candidate;
            }
        }

        if (count($imeis) < $target) {
            $this->command?->warn('Gagal membuat 100 IMEI unik.');
            return;
        }

        $assetRows = [];
        $locationRows = [];

        foreach ($imeis as $imei) {
            $conditions = ['good', 'minor', 'major', 'broker'];
            $condition = $conditions[array_rand($conditions)];

            $assetData = [
                'master_asset_id' => $masterId,
                'gps_number' => $imei,
                'condition' => $condition,
                'purchase_date' => $now->copy()->subDays(random_int(0, 365))->toDateString(),
                'purchase_price' => random_int(800000, 4000000),
                'warranty_expired' => $now->copy()->addDays(random_int(90, 1095))->toDateString(),
                'notes' => 'Seeded for tracking test',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($hasAssetCode) {
                $assetData['asset_code'] = 'KA' . str_pad((string) $nextNo, 3, '0', STR_PAD_LEFT);
                $nextNo++;
            }

            $assetRows[] = $assetData;

            $area = $areas[array_rand($areas)];
            $latOffset = ((mt_rand() / mt_getrandmax()) * 2 - 1) * $area['radius'];
            $lngOffset = ((mt_rand() / mt_getrandmax()) * 2 - 1) * $area['radius'];
            $uploadTime = $now->copy()->subMinutes(random_int(0, 60 * 24 * 7));

            $locationRows[] = [
                'gps_number' => $imei,
                'latitude' => round($area['lat'] + $latOffset, 7),
                'longitude' => round($area['lng'] + $lngOffset, 7),
                'uploadtime' => $uploadTime,
                'electricity' => random_int(15, 100),
                'timestamp' => $uploadTime->copy()->addSeconds(random_int(0, 120)),
            ];
        }

        DB::table('assets')->insert($assetRows);
        DB::table('asset_locations')->insert($locationRows);

        $this->command?->info('Berhasil menambahkan 100 assets dan 100 asset_locations.');
    }
}
