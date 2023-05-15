<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeoResource\Pages;
use App\Models\Geo;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
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
        return $form->columns(3)
            ->schema([
                Grid::make()->columnSpan(2)->schema([

                    Card::make()->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->required(),
                    ]),
                ]),

                Grid::make()->columnSpan(1)->schema([
                    Card::make()->schema([
                        Select::make('parent_id')
                            ->label('Родительский тип')
                            ->options(function (?Geo $record) {
                                return Geo::where('parent_id', null)->where('id', '!=', $record?->id)
                                    ->get()
                                    ->pluck('name', 'id');
                            })->visible(function (?Geo $record) {
                                return !$record?->children()->exists();
                            })->dehydrated(function (?Geo $record) {
                                return !$record?->children()->exists();
                            }),
                            Placeholder::make('has_children')
                                ->label('У данной области есть дочерние области')
                                ->visible(function (?Geo $record) {
                                return $record?->children()->exists();
                            }),
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Название')->sortable()->searchable(),
                TextColumn::make('parent.name')->label('Родительский тип')->sortable()->searchable(),
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
