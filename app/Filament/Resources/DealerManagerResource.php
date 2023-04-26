<?php

namespace App\Filament\Resources;

use App\Filament\BaseResource\UserResource;
use App\Filament\Resources\DealerManagerResource\Pages;
use Filament\Resources\Form;

class DealerManagerResource extends UserResource
{
    protected static ?string $slug = 'dealer_managers';

    protected static ?string $modelLabel = 'Менеджер Дилера';
    protected static ?string $pluralModelLabel = 'Менеджеры Дилера';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $role = 'dealer_manager';

    protected static bool $hasLogo = true;

    protected static ?int $role_id = 2;


    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            ...static::baseFields(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDealerManagers::route('/'),
            'create' => Pages\CreateDealerManager::route('/create'),
            'edit' => Pages\EditDealerManager::route('/{record}/edit'),
        ];
    }
}
