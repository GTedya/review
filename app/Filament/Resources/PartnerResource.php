<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Партнер';

    protected static ?string $pluralModelLabel = 'Партнеры';

    public static function form(Form $form): Form
    {
        return $form->columns(3)
            ->schema([
                Grid::make()->columnSpan(2)->schema([

                    Card::make()->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->required(),

                        TextInput::make('link')
                            ->label('Ссылка'),
                    ]),
                ]),

                Grid::make()->columnSpan(1)->schema([
                    Card::make()->schema([
                        Fieldset::make('Изображение')->columns(1)->schema([
                            SpatieMediaLibraryFileUpload::make('logo')
                                ->image()
                                ->enableOpen()
                                ->disableLabel()
                                ->label('Лого')
                                ->directory('form-tmp')
                                ->collection('logo')
                                ->required(),
                        ]),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Название')->sortable()->searchable(),
                SpatieMediaLibraryImageColumn::make('logo')->collection('logo')->label('Лого'),
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
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
