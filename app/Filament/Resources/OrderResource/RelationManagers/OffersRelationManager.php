<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\ManagerOffer;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;

class OffersRelationManager extends RelationManager
{
    protected static string $relationship = 'offers';
    protected static ?string $pluralModelLabel = 'Коммерческие предложения';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('manager.name')->label('Пользователь'),
                IconColumn::make('Ссылка')
                    ->url(function (ManagerOffer $record) {
                        return $record->getFirstMediaUrl('offer_file');
                    })
                    ->openUrlInNewTab()
                    ->options(function (ManagerOffer $record) {
                        $url = $record->getFirstMediaUrl('offer_file');
                        if (blank($url)) {
                            return ['heroicon-o-ban'];
                        }
                        return ['heroicon-o-external-link'];
                    }),
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
