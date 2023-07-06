<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingsResource;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function __construct(public SettingService $settingService)
    {
    }

    public function getInfo(): JsonResponse
    {
        /** @var Setting $settings */
        $settings = $this->settingService->getSettingsInfo();

        return response()->json(['success' => true, 'info' => SettingsResource::make($settings)]);
    }
}
