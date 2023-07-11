<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CallService
{
    private const CALL_BASE_URL = "https://api.unibell.ru/apps/flash/calls/flash";
    private const ACCESS_KEY = "Basic pQv29uazeivPZwAQGo9PHl8tA6H4lkeG";

    public function flashCall(string $phone, string $code): void
    {
        Http::withHeaders([
            'Authorization' => self::ACCESS_KEY,
            'Content-Type' => 'application/json',
        ])
            ->post(
                self::CALL_BASE_URL,
                [
                    'number' => $phone,
                    'code' => $code,
                    'timeout' => 10000
                ]
            );
    }
}
