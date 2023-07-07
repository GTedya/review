<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingsRepo
{
    public function getInfo(): ?Setting
    {
        return Setting::query()->first();
    }
}
