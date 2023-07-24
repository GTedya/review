<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeasingResource\Pages;
use App\Models\Leasing;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class LeasingResource extends Resource
{
    protected static ?string $model = Leasing::class;

    protected static ?string $modelLabel = 'Вид Лизинга';
    protected static ?string $pluralModelLabel = 'Виды Лизинга';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            Grid::make()->columnSpan(2)->schema([

                Card::make()->schema([
                    Grid::make()->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->required(),

                        TextInput::make('link')
                            ->label('Ссылка')
                            ->required(),
                    ]),

                    Repeater::make('items')->label('Элементы списка')->schema([
                        TextInput::make('text')->disableLabel()->required(),
                    ]),

                    Fieldset::make('Изображение')->columns(1)->schema([
                        SpatieMediaLibraryFileUpload::make('image')
                            ->image()
                            ->enableOpen()
                            ->disableLabel()
                            ->label('Изображение')
                            ->directory('form-tmp')
                            ->collection('image')
                            ->panelLayout('integrated'),
                    ]),
                ]),
            ]),
            Card::make()->columnSpan(1)->schema([
                TextInput::make('sort_index')
                    ->label('Индекс сортировки')
                    ->default(500),
            ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_index')->label('Индекс сортировки')->sortable(),
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
            'index' => Pages\ListLeasings::route('/'),
            'create' => Pages\CreateLeasing::route('/create'),
            'edit' => Pages\EditLeasing::route('/{record}/edit'),
        ];
    }
}
