<?php

namespace App\Filament\Resources;

use App\Filament\BaseResource\UserResource;
use App\Filament\Resources\LeasingManagerResource\Pages;
use Filament\Resources\Form;

class LeasingManagerResource extends UserResource
{
    protected static ?string $slug = 'leasing_managers';

    protected static ?string $modelLabel = 'Менеджер Лизинга';
    protected static ?string $pluralModelLabel = 'Менеджеры Лизинга';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $role = 'leasing_manager';

    protected static bool $hasLogo = true;

    protected static ?int $role_id = 3;


    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            ...static::baseFields(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeasingManagers::route('/'),
            'create' => Pages\CreateLeasingManager::route('/create'),
            'edit' => Pages\EditLeasingManager::route('/{record}/edit'),
        ];
    }
}
