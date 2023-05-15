<?php

namespace App\Filament\Resources\RentResource\Pages;

use App\Filament\Resources\RentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRent extends CreateRecord
{
    protected static string $resource = RentResource::class;

    public function getTitle(): string
    {
        return 'Создать объявление аренды';
    }
}
