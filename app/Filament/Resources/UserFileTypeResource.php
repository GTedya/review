<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserFileTypeResource\Pages;
use App\Models\UserFileType;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class UserFileTypeResource extends Resource
{
    protected static ?string $model = UserFileType::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Тип файлов пользователя';
    protected static ?string $pluralModelLabel = 'Типы файлов пользователя';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->columns(1)->schema([
                    Card::make()->schema([
                            TextInput::make('name')
                                ->label('Название')
                                ->required(),
                            CheckboxList::make('org_type')
                                ->label('Типы организаций')
                                ->options([
                                    'ip' => 'ИП',
                                    'ooo' => 'ООО',
                                ])
                                ->required(1),
                            Toggle::make('show_in_order')
                        ]
                    )
                ]),
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
            'index' => Pages\ListUserFileType::route('/'),
            'create' => Pages\CreateUserFileType::route('/create'),
            'edit' => Pages\EditUserFileType::route('/{record}/edit'),
        ];
    }
}
