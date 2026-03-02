<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['lat' => -6.2088, 'lng' => 106.8456, 'radius' => 0.03], // Jakarta Pusat
            ['lat' => -6.1745, 'lng' => 106.8227, 'radius' => 0.04], // Jakarta Utara
            ['lat' => -6.2615, 'lng' => 106.8106, 'radius' => 0.04], // Jakarta Selatan
            ['lat' => -6.2250, 'lng' => 106.9004, 'radius' => 0.04], // Jakarta Timur
            ['lat' => -6.1683, 'lng' => 106.7588, 'radius' => 0.04], // Jakarta Barat
            ['lat' => -6.4025, 'lng' => 106.7942, 'radius' => 0.05], // Depok
            ['lat' => -6.1783, 'lng' => 106.6319, 'radius' => 0.05], // Tangerang
            ['lat' => -6.2886, 'lng' => 106.7176, 'radius' => 0.05], // Tangerang Selatan
            ['lat' => -6.2383, 'lng' => 106.9756, 'radius' => 0.05], // Bekasi
            ['lat' => -6.5950, 'lng' => 106.8167, 'radius' => 0.06], // Bogor
        ];

        $gpsNumbers = DB::table('assets')
            ->whereNotNull('gps_number')
            ->pluck('gps_number')
            ->values()
            ->all();

        $rows = [];
        for ($i = 1; $i <= 500; $i++) {
            $area = $areas[array_rand($areas)];
            $offsetLat = $this->randomOffset($area['radius']);
            $offsetLng = $this->randomOffset($area['radius']);
            $time = now()->subMinutes(random_int(0, 60 * 24 * 14));

            $rows[] = [
                'gps_number' => $gpsNumbers
                    ? $gpsNumbers[array_rand($gpsNumbers)]
                    : '628' . str_pad((string) $i, 12, '0', STR_PAD_LEFT),
                'latitude' => round($area['lat'] + $offsetLat, 7),
                'longitude' => round($area['lng'] + $offsetLng, 7),
                'uploadtime' => $time,
                'electricity' => random_int(15, 100),
                'timestamp' => $time->copy()->addSeconds(random_int(0, 120)),
            ];
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('asset_locations')->insert($chunk);
        }
    }

    private function randomOffset(float $radius): float
    {
        return (mt_rand() / mt_getrandmax() * 2 - 1) * $radius;
    }
}
