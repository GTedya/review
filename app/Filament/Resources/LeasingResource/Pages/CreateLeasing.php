<?php

namespace App\Filament\Resources\LeasingResource\Pages;

use App\Filament\Resources\LeasingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeasing extends CreateRecord
{
    protected static string $resource = LeasingResource::class;

    public function getTitle(): string
    {
        return 'Создать вид лизинга';
    }
}
