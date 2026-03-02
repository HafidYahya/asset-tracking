<?php

namespace App\Services;

use App\Models\TracksolidToken;
use App\Models\Location;
use Illuminate\Support\Facades\Http;


class TracksolidService
{
    /**
     * Create a new class instance.
     */

    protected $baseUrl;
    public function __construct()
    {
        $this->baseUrl = config('services.tracksolid.base_url');
    }

    public function getRealtimeLocation($imei)
    {
        $token = $this->getValidToken();

        $params = [
            'access_token' => $token,
            'app_key' => config('services.tracksolid.app_key'),
            'format' => 'json',
            'imeis' => $imei,
            'method' => 'jimi.device.location.get',
            'sign_method' => 'md5',
            'timestamp' => now()->utc()->format('Y-m-d H:i:s'),
            'v' => '1.0',
            'map_type' => 'GOOGLE',
        ];

        $params['sign'] = $this->generateSign($params);

        $response = Http::asForm()->post($this->baseUrl, $params)->json();

        // Token invalid
        if (isset($response['code']) && $response['code'] == 1004) {
            $this->login();
            return $this->getRealtimeLocation($imei);
        }

        return $response;
    }
    public function storeLocation($data)
    {
        if (!isset($data['result'])) return;

        foreach ($data['result'] as $device) {
            $imei = $device['imei'] ?? null;
            if (!$imei) {
                continue;
            }

            Location::where('imei', $imei)->delete();

            Location::create([
                'imei' => $imei,
                'status' => $device['status'] ?? null,
                'lat' => $device['lat'] ?? null,
                'lng' => $device['lng'] ?? null,
                'electQuantity' => $device['electQuantity'] ?? null,
            ]);
        }
    }

    public function getValidToken()
    {
        $token = TracksolidToken::first();

        if (!$token) {
            return $this->login();
        }

        if (now()->addMinute()->gte($token->expired_at)) {

            $newToken = $this->refresh();

            if (!$newToken) {
                return $this->login();
            }

            return $newToken;
        }

        return $token->access_token;
    }

    private function login()
    {
        $params = [
            'app_key' => config('services.tracksolid.app_key'),
            'method' => 'jimi.oauth.token.get',
            'timestamp' => now()->utc()->format('Y-m-d H:i:s'),
            'v' => '1.0',
            'format' => 'json',
            'sign_method' => 'md5',
            'user_id' => config('services.tracksolid.user_id'),
            'user_pwd_md5' => md5(config('services.tracksolid.password')),
            'expires_in' => 7200,
        ];

        $params['sign'] = $this->generateSign($params);

        $response = Http::asForm()->post($this->baseUrl, $params)->json();

        if ($response['code'] !== 0) {
            throw new \Exception('Tracksolid login failed');
        }

        $this->storeToken($response['result']);

        return $response['result']['accessToken'];
    }

    private function refresh()
    {
        $token = TracksolidToken::first();

        $params = [
            'app_key' => config('services.tracksolid.app_key'),
            'method' => 'jimi.oauth.token.refresh',
            'timestamp' => now()->utc()->format('Y-m-d H:i:s'),
            'v' => '1.0',
            'format' => 'json',
            'sign_method' => 'md5',
            'refresh_token' => $token->refresh_token,
        ];

        $params['sign'] = $this->generateSign($params);

        $response = Http::asForm()->post($this->baseUrl, $params)->json();

        if ($response['code'] !== 0) {
            return false;
        }

        $this->storeToken($response['result']);

        return $response['result']['accessToken'];
    }

    private function storeToken($data)
    {
        TracksolidToken::updateOrCreate(
            ['id' => 1],
            [
                'access_token' => $data['accessToken'],
                'refresh_token' => $data['refreshToken'],
                'expired_at' => now()->addSeconds($data['expiresIn']),
            ]
        );
    }
    private function generateSign($params)
    {
        ksort($params);

        $string = config('services.tracksolid.app_secret');

        foreach ($params as $k => $v) {
            $string .= $k . $v;
        }

        $string .= config('services.tracksolid.app_secret');

        return strtoupper(md5($string));
    }
}
