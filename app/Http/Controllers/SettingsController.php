<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingsResource;
use App\Models\Setting;
use App\Repositories\SettingsRepo;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function __construct(private SettingsRepo $settingsRepo)
    {
    }

    public function getInfo(): JsonResponse
    {
        /** @var Setting $settings */
        $settings = $this->settingsRepo->getInfo();

        return response()->json(['success' => true, 'info' => SettingsResource::make($settings)]);
    }
}
