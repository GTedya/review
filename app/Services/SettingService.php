<?php

namespace App\Services;

use App\Repositories\SettingsRepo;

class SettingService
{
    public function __construct(private SettingsRepo $settingsRepo)
    {
    }

    public function getSettingsInfo(): object|null
    {
        return $this->settingsRepo->getInfo();
    }
}
