<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\User;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ManagersRelationManager extends RelationManager
{
    protected static string $relationship = 'managers';

    protected static ?string $pluralModelLabel = 'Менеджеры принявшие в работу';

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('ФИО'),
                Tables\Columns\TextColumn::make('roles.name')->label('Менеджер')->getStateUsing(
                    function (User $record) {
                        return User::ROLE_NAMES[$record->getRoleNames()->get(0)];
                    }
                )->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
