<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeoResource\Pages;
use App\Models\Geo;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class GeoResource extends Resource
{
    protected static ?string $model = Geo::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Область';
    protected static ?string $pluralModelLabel = 'Области';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->columnSpan(2)->schema([
                    TextInput::make('name')
                        ->label('Название')
                        ->required()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Название')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeos::route('/'),
            'create' => Pages\CreateGeo::route('/create'),
            'edit' => Pages\EditGeo::route('/{record}/edit'),
        ];
    }
}
