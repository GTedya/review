<?php

namespace App\Filament\Resources\VehicleTypeResource\Pages;

use App\Filament\Resources\VehicleTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVehicleType extends CreateRecord
{
    protected static string $resource = VehicleTypeResource::class;

    public function getTitle(): string
    {
        return 'Создать тип';
    }
}
