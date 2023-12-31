<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleTypeResource\Pages;
use App\Models\VehicleType;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class VehicleTypeResource extends Resource
{
    protected static ?string $model = VehicleType::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Тип транспортных средств';
    protected static ?string $pluralModelLabel = 'Типы транспортных средств';

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
                    Select::make('parent_id')
                        ->label('Родительский тип')
                        ->options(function (?VehicleType $record) {
                            if ($record !== null) {
                                $ids = array_reduce(
                                    $record->children()->with(static::recursiveWith())->get()->toArray(),
                                    static::recursiveReduce(),
                                    [],
                                );

                                $ids[] = $record->id;
                            }

                            return VehicleType::whereNotIn('id', $ids ?? [])
                                ->get()
                                ->pluck('name', 'id');
                        })
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
            'index' => Pages\ListVehicleTypes::route('/'),
            'create' => Pages\CreateVehicleType::route('/create'),
            'edit' => Pages\EditVehicleType::route('/{record}/edit'),
        ];
    }

    private static function recursiveWith(): array
    {
        return [
            'children' => function ($query) {
                $query->with(static::recursiveWith());
            }
        ];
    }

    private static function recursiveReduce()
    {
        return function ($result, $item) {
            $result[] = $item['id'];
            $reduced = array_reduce($item['children'], static::recursiveReduce(), []);
            return array_merge($result, $reduced);
        };
    }
}
