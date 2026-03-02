<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TracksolidService;
use App\Models\Asset;
use Illuminate\Support\Facades\Log;


class TrackDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track:devices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track all devices';

    /**
     * Execute the console command.
     */
    public function handle(TracksolidService $tracksolid)
    {
        $imeis = Asset::whereNotNull('gps_number')->pluck('gps_number');

        foreach ($imeis as $imei) {
            $data = $tracksolid->getRealtimeLocation($imei);

            if (isset($data['code']) && $data['code'] == 0) {
                $tracksolid->storeLocation($data);
            }
            // di dalam foreach
            $data = $tracksolid->getRealtimeLocation($imei);

            Log::info('tracksolid.response', [
                'imei' => $imei,
                'code' => $data['code'] ?? null,
                'message' => $data['message'] ?? null,
                'has_result' => isset($data['result']),
            ]);
        }

        $this->info('Tracking complete.');
    }
}
