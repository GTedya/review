<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingsRepo
{
    public function getInfo(): object|null
    {
        return Setting::query()->first();
    }
}
